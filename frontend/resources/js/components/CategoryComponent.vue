<template>
    <div>
        <div class="row">
            <h2>{{ this.category ?'Category: '+this.category.name:'' }}</h2>
        </div>

        <div v-if="products.length==0">
            <h2 class="no-products">No products on this category</h2>
        </div>

        <div class="row" id="products">
            <div class="col-sm-12">
                <product-card v-for="(product,index) in products" v-bind:key="product.id" v-model="products[index]" v-bind:rates="exchangeRates" @click.native="goToDetail(product)"></product-card>
            </div>
        </div>

        <CPagination
            v-if="products.length>0"
            :active-page.sync="current_page"
            :pages="last_page"
            size="lg"
            align="center"

        ></CPagination>

    </div>
</template>

<script>
    import ProductCard from "./ProductCard";
    import {remoteServer} from "../globals";

    export default {
        name: "CategoryComponent",
        components: {ProductCard},
        data(){
            return {
                products:[],
                category:null,
                exchangeRates:{},
                current_page:1,
                last_page:1,
                baseAjax:'',

            }
        },
        props: {
            default_img:{type:String}

        },
        created(){
            this.baseAjax= remoteServer.baseAjax+'/categories';
            this.getCurrencyRate()
            let url= this.baseAjax+'/'+this.$route.params.id;
            axios.get(url).then(response=>{
                this.category=response.data.data
            });

            this.populate();

        },
        methods:{
            populate(){
                let url= this.baseAjax+'/'+this.$route.params.id;
                url= url+'/products';
                let options={
                    params:{
                        page:this.current_page
                    }
                };

                axios.get(url,options).then(response=>{
                    this.products=response.data.data;
                    this.last_page=response.data.meta.last_page;
                });
            },
            goToDetail(product){
                this.$router.push({
                    name:'product',
                    params:
                        {
                            id:product.id
                        }
                });
            }
        },
        watch:{
            current_page:function(newVal,oldVal){
                this.populate();
            }
        }
    }
</script>

<style scoped>

</style>
