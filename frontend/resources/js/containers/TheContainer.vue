<template>
    <div class="c-app">
        <TheSidebar v-bind:childs="childs"/>
        <div class="c-wrapper">
            <div class="c-body">
                <main class="c-main">
                    <CContainer fluid>
                        <transition name="fade">
                                <router-view></router-view>
                        </transition>
                    </CContainer>
                </main>
            </div>
            <TheFooter/>
        </div>
    </div>
</template>

<script>
    import TheSidebar from './TheSidebar'
    import TheFooter from './TheFooter'
    import {remoteServer} from "../globals";


    export default {
        name: 'TheContainer',
        components: {
            TheSidebar,
            TheFooter
        },
        data(){
          return {
              childs:[],
              name:'',
              baseAjax:'',

          }
        },
        props: {
            default_img:{type:String},

        },
        created(){
            if(this.$route.name=='category' || this.$route.name=='home'){
                this.baseAjax = remoteServer.baseAjax+'/categories';
                let url= this.baseAjax;
                let options={}
                if(this.$route.params.id!=undefined){
                    url=url+'/'+this.$route.params.id
                }else{
                    options.params={
                        top:true
                    }
                }

                axios.get(url,options).then(response=>{
                    if(Array.isArray(response.data.data)){
                        this.childs=response.data.data;

                    }else{
                        this.childs=response.data.data.childs;
                        this.name=response.data.data.name;
                    }
                })
            }

        }
    }
</script>

<style scoped>
    .fade-enter-active,
    .fade-leave-active {
        transition: opacity 0.3s;
    }
    .fade-enter,
    .fade-leave-to {
        opacity: 0;
    }
</style>
