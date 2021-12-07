<script>
export default {

    data() {
        return {
            page: 1,
            selected: [],
            parentChecked: false,
            projects: [],
            check: 'scroll',
            delete_ids_transactions: [],
        };
    },
    
    methods: {
        getData($state, status = null) {
            this.selectAll();
            this.check = 'scroll';
            // let loader = this.$loading.show();

            let fData = JSON.stringify(this.filteredData);
            this.$api
                .get(this.base_url + `/scroll?page=${this.page}` , { params: {fData: fData} })
                .then(response => {
                    // loader.hide();
                    let newData = response.data.data;
                    this.checkedAll = false;
                    
                    if (newData.length > 0) {
                        this.page += 1;
                        this.projects.push(...newData);
                        $state.loaded();
                    } else {
                        $state.complete();
                    }
                })
                .catch(errors => {
                    // loader.hide();
                });
            
        },

        filterFun(status = null) {
            this.selectAll();            
            let loader = this.$loading.show();
            let fData = JSON.stringify(this.filteredData);            
            if(status == 'delete'){
                this.page -= 1;
                this.$api
                .get(this.base_url + `/del?page=${this.page}` , { params: {fData: fData} })
                .then(response => {
                    this.page +=1;
                    loader.hide();
                    this.projects = response.data;
                    this.checkedAll = false;
                })
                .catch(errors => {
                    loader.hide();
                });
            }
           
            else if(status == 'change_status'){
                this.check = 'change_status';
                this.$api
                .get(this.base_url + `/change_status?page=${this.page}` , { params: {fData: fData} })
                .then(response => {
                    loader.hide();
                    this.projects = response.data;
                    this.checkedAll = false;
                })
                .catch(errors => {
                    loader.hide();
                });
            }
            else{
                this.check = 'filter';
                this.$api
                .get(this.base_url + `/filter?page=${this.page}` , { params: {fData: fData} })
                .then(response => {
                    loader.hide();
                    this.projects = response.data;
                    this.checkedAll = false;
                })
                .catch(errors => {
                    loader.hide();
                });
            }              
        },

        deleteData(_status) { 
            // alert(status)
            this.$alertService.showConfirmDialog(null,this.$t('alertMessage.delete_confirm_message'),this.$t('alertMessage.yes'),this.$t('alertMessage.no'))
            .then((r) => {
                if(r.value){
                    //  let checkedData = {};
                    //  let jobStatus = {};
                    // this.$set(checkedData, "checked_data", this.selected);
                    // this.$set(jobStatus, "status",_status);
                    this.$api
                    .post(this.base_url + '/delete',{checked_data:this.selected,status:_status})
                    .then((response) => {
                        // console.log(response.data.data)
                        this.delete_ids_transactions = [];
                        if(Array.isArray(response.data.data) && response.data.data.length > 0){
                            this.delete_ids_transactions = response.data.data;
                            this.$alertService.showErrorDialog(null, "関連している他のデータが存在するため、削除できない求人があります。");
                        }
                        this.filterFun('delete');
                    })
                    .catch(errors => {
                      
                    });
                }
                
            })

        },

        selectAll: function(payload) {
            this.selected = [];
            if (payload) {
                console.log("selectAll pay",payload)
                for (let i in this.projects) {
                    this.selected.push(this.projects[i].id);
                }
            } else {
                this.$refs.datatable.uncheck();
                this.selected = [];
            }
        }
    },
    computed: {
        isDisabled() {
            //if dont select any row, set disable delete button
            return this.selected.length > 0 ? false : true;
        },
    },
};
</script>