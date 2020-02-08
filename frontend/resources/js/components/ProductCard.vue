<template>
    <CCard>
        <CCardHeader>
            {{ product.name }}
        </CCardHeader>
        <CCardBody>
            <div class="row">
                <div class="col-sm-3 col-lg-2 col-md-3 col-xl-1">
                    <img v-if="product.images.length==0" src="https://btl-shop.com/wp-content/uploads/2019/01/default-pro.jpg"  class="img-thumbnail">
                    <img v-if="product.images.length>0" :src="remoteServer.baseUrl+product.images[0]"  class="img-thumbnail">
                </div>
                <div class="col-sm-9 col-lg-10 col-md-9 col-xl-11" >
                    <div>
                        <label><b>USD Price:</b><money-format :value="product.usd_price" locale='en' currency-code='USD'></money-format></label>
                    </div>
                    <div>
                        <label><b>COP Price:</b><money-format :value="rates && rates['USDCOP']?product.usd_price*rates['USDCOP']:product.usd_price" locale='en' currency-code='COP'></money-format></label>
                    </div>
                    <p>{{ product.description | truncate(200) }}</p>
                </div>
            </div>

        </CCardBody>

    </CCard>
</template>

<script>

    import {remoteServer} from '../globals';
    import MoneyFormat from 'vue-money-format';

    export default {
        name: "ProductCard",
        props: {
            'product':{type: Object},
            'rates':{type:Object}
        },
        model:{
            prop:'product',
            default:{}
        },
        data(){
          return {
              remoteServer:remoteServer
          }
        },
        components:{
            'money-format': MoneyFormat
        }

    }
</script>

<style scoped>

</style>
