<template>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
            
               <th v-if="showCheckbox">
               <input type="checkbox" @click="emitToParent" v-model="checkornot" />
               </th>
                <th v-for="column in columns" :key="column.name" 
                    :style="'width:'+column.width+';'">
                    {{column.label}}
                </th>
            </tr>
        </thead>
        <slot></slot>
    </table>
</template>

<script>
    export default {
        
        props: ['columns', 'sortKey', 'sortOrders','removeChecked','showCheckbox'],
        data() {
            return {
               checkornot:false,
            }
        },
        methods:{
           

            emitToParent(){
               if(this.checkornot == false)
               {
                   this.checkornot = true;
               }
               else{
                   this.checkornot = false;
               }
                this.$emit('check-all',this.checkornot)
            },
            uncheck()
            {
                this.checkornot = false;
            }
        }
    }
</script>
