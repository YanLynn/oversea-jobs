<template>
    <div class="page-wrap d-flex flex-row align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 text-center">
                <span class="display-1 d-block txt-404">404</span>
                <div class="mb-4 lead">Page Not Found</div>
                <a href="/" class="btn btn-link">Back to Home</a>
            </div>
        </div>
    </div>
</div>

</template>
<script>
export default {
	metaInfo: {
		title: 'BORDERLESS WORKING（ボーダレスワーキング）海外就職支援',
	},
}
</script>
<style scoped>
.page-wrap {
    min-height: 100vh;
}
.txt-404{
    font-size: 15em;
}
.lead{
    font-size: 3rem;
    color:#7b7b7b;
}
.btn-link{
    width: 150px;
    border-radius: 25px !important;
    background:#0062ff;
}
.btn-link:hover{
    text-decoration: none;
}
@media screen and (max-width: 767px){
    .txt-404{
        font-size: 12em;
    }
    .lead{
        font-size: 2em;
    }
}
</style>