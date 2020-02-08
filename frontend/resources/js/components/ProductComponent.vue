<template>
    <CCard>
        <CCardHeader>
            {{ product.name }}
        </CCardHeader>
        <CCardBody class="product">

            <div class="row meta">

                    <div>
                        <label><b>USD Price:</b><money-format :value="product.usd_price" locale='en' currency-code='USD'></money-format></label>
                    </div>
                    <div>
                        <label><b>COP Price:</b><money-format :value="exchangeRates && exchangeRates['USDCOP']?product.usd_price*exchangeRates['USDCOP']:product.usd_price" locale='en' currency-code='COP'></money-format></label>
                    </div>

                    <div>
                        <label><b>Weight:</b>{{ product.weight }}</label>
                    </div>
                    <div>
                        <label><b>Category: </b>{{ product.category.name }}</label>
                    </div>


            </div>

            <div class="row">

            </div>
            <p>{{ product.description }}</p>
            <div>
                <h2>Product images</h2>
                <img v-for="(image,index) in product.images" v-bind:key="index"  :src="remoteBase+image" class="img-fluid product-img">
            </div>
        </CCardBody>
    </CCard>
</template>

<script>
    import {remoteServer} from "../globals";
    import MoneyFormat from 'vue-money-format';


    export default {
        name: "ProductComponent",
        created() {
            this.getCurrencyRate()
            this.populate()
        },
        data(){
          return{
              baseAjax:'',
              remoteBase:'',
              product:{
                  usd_price:0,
                  description:''
              },
              exchangeRates:{}
          }
        },
        components:{
            'money-format': MoneyFormat,
        },
        methods:{
            populate(){
                this.baseAjax=remoteServer.baseAjax;
                this.remoteBase=remoteServer.baseUrl;
                let url= this.baseAjax+'/products/'+this.$route.params.id;

                let options={};

                axios.get(url,options).then(response=>{
                    this.product=response.data.data;
                });
            }
        }
    }
</script>

<style scoped>

</style>
