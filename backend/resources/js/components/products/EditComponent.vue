// NewComponent.vue

<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-default">
                <div class="card-header">Create Product</div>

                <div class="card-body">

                    <form @submit.prevent="update">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Product Name:</label>
                                    <input type="text" class="form-control" v-model="name">
                                    <div class="error" v-if="!$v.name.required">Name is required</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Product Description:</label>
                                    <textarea rows="10" class="form-control" v-model="description">
                                    </textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Product Weight:</label>
                                    <input type="number" class="form-control" v-model="weight">
                                    <div class="error" v-if="!$v.weight.required">Weight is required</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Product Price in USD:</label>
                                    <input type="text" class="form-control" v-model="usd_price">
                                    <div class="error" v-if="!$v.usd_price.required">price is required</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Product Category:</label>
                                    <v-select @search="fetchLeafCategories" v-model="category" :options="categories" label="name"></v-select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Product Images:</label>
                                    <base64-image-wrapper v-for="index in maxImages" v-bind:key="index" :onLoadCallback="onImageLoad" :id="index" :default_img="images_urls[index-1]?images_urls[index-1]:default_img" />
                                </div>
                            </div>
                        </div>


                        <br />
                        <div class="form-group">
                            <button class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import Vue from "vue";
    import VueBase64FileUpload from 'vue-base64-file-upload'
    import vSelect from 'vue-select'
    import Vuelidate from 'vuelidate'
    import FormData from 'form-data'
    Vue.use(Vuelidate)
    import { required, minLength,maxLength } from 'vuelidate/lib/validators'
    import Base64ImageWrapper from "../Base64ImageWrapper";



    export default {
        mounted() {
            this.fetchLeafCategories('',function (dum) {});
        },
        data(){
            return {
                name:'',
                description:'',
                weight:0,
                usd_price:0,
                categories:[],
                category:null,
                images:[],
                images_urls:[],
                default_img:"https://btl-shop.com/wp-content/uploads/2019/01/default-pro.jpg",
                maxImages:3
            }
        },
        created() {
            let uri = `/api/products/${this.$route.params.id}`;
            this.axios.get(uri).then((response) => {
                this.name = response.data.data.name;
                this.description = response.data.data.description;
                this.weight=response.data.data.weight;
                this.usd_price=response.data.data.usd_price;
                this.category=response.data.data.category;
                this.images_urls=response.data.data.images;
                let scope=this;
                this.categories.forEach(function (category) {
                    if(scope.category.id==category.id){
                        scope.category=category;
                    }
                });


            });
        },
        components: {
            Base64ImageWrapper,
            VueBase64FileUpload,
            vSelect:vSelect,
            Vuelidate,
            FormData:FormData
        },
        validations:{
            name:{
                required,
            },
            description:{
                required,
            },
            weight:{
                required,
            },
            usd_price:{
                required,
            },
            category:{
                required
            }

        },
        methods: {

            fetchLeafCategories:function (search,loading) {
                loading(true);
                axios.get('/api/categories',{
                    params:{
                        leaf:true
                    }
                }).then(res => {
                    this.categories=res.data.data;
                    if(this.category!=null && this.category!=undefined){
                        let scope=this
                        this.categories.forEach(function (category) {
                                if(scope.category.id==category.id){
                                    scope.category=category;
                                }
                                })
                    }
                    loading(false);
                });
            },

            onImageLoad:function(urldata,index){
                this.images[index-1]=urldata
            },

            update:function () {

                this.$v.$touch();
                if (!this.$v.$invalid) {

                    var data={
                        name:this.name,
                        description:this.description,
                        weight:this.weight,
                        usd_price:this.usd_price,
                        category_id:this.category.id,
                        images:[],
                    }

                    for(var index=0;index<this.maxImages;index++){
                            let img = this.images[index];
                            if(img==undefined || img==null){
                                data.images.push(null);
                            }else{
                                data.images.push(img);
                            }
                    }

                    return axios.put('/api/products/'+this.$route.params.id, data).then(response =>{
                        this.$router.push({name: 'products_index'});
                    }).catch(error=> {
                        for(const error_key in error.response.data.errors){
                            for(const error_message in error.response.data.errors[error_key]){
                                alert(error.response.data.errors[error_key][error_message]);
                            }
                        }
                    });
                }
            }
        }
    }

</script>
