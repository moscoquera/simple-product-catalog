// App.vue

<template>
    <transition name="fade">
        <router-view :key="$route.path"></router-view>
    </transition>
</template>

<style>
    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s
    }
    .fade-enter, .fade-leave-active {
        opacity: 0
    }
</style>

<script>

    import {currency} from "./globals";
    import Vue from 'vue';

    let jsonpAdapter = require('axios-jsonp');

    Vue.mixin({
        methods: {
            currencyConverter: function (amount,target,source) {
                if(source==undefined){
                    source=currency.base
                }

                if(target==undefined){
                    target=currency.to
                }

                if(target==source){
                    return amount;
                }

                let key = source+target
                let rate = this.exchangeRates?this.exchangeRates[key]:0;

                if(rate){
                    return amount*rate;
                }

                return 0;
            },
            getCurrencyRate:function(){
                let self=this
                axios.get(currency.endpoint,{
                    adapter: jsonpAdapter,
                    params:{
                        access_key:currency.key,
                        source:currency.base,
                        currencies:currency.to
                    }
                }).then(response=>{
                    if(response.data.success){
                        self.exchangeRates=response.data.quotes;
                    }
                })
            }
        }
    });

    export default{
        data(){
            return {

            }
        },
        created(){

        },
        methods:{
        }
    }
</script>
