// NewComponent.vue

<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-default">
                <div class="card-header">Create Category</div>

                <div class="card-body">

                    <form @submit.prevent="add">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Category Name:</label>
                                    <input type="text" class="form-control" v-model="name">
                                    <div class="error" v-if="!$v.name.required">Name is required</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Category Image:</label>
                                    <br/>
                                    <vue-base64-file-upload
                                        class="v1"
                                        accept="image/png,image/jpeg"
                                        image-class="v1-image"
                                        input-class="v1-input"
                                        :default-preview="image_url"
                                        :max-size="1"
                                        @file="onImageFile"
                                        @load="onImageLoad" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Parent Category:</label>
                                    <v-select @search="fetchCategories" v-model="parent" :options="categories"
                                    label="name"
                                    ></v-select>
                                </div>
                            </div>
                        </div>

                        <br />
                        <div class="form-group">
                            <button class="btn btn-primary">Create</button>
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
    Vue.use(Vuelidate)
    import { required, minLength,maxLength } from 'vuelidate/lib/validators'



    export default {
        mounted() {
            this.fetchCategories('',function (dum) {});
        },
        data(){
            return {
                name:'',
                parent:null,
                image:null,
                image_url:'https://www.gravatar.com/avatar/default?s=200&r=pg&d=mm',
                categories:[]
            }
        },
        components: {
            VueBase64FileUpload,
            vSelect:vSelect,
            Vuelidate
        },
        validations:{
            name:{
                    required,
                }
        },
        methods: {

            fetchCategories:function (search,loading) {
                loading(true);
                axios.get('/api/categories').then(res => {
                    let selected=null;

                    for(let category in res.data.data){
                        if(this.parent && res.data.data[category].id==this.parent.id){
                            selected=res.data.data[category];
                        }
                    }

                    this.categories=res.data.data;
                    loading(false);
                });
            },

            onImageFile:function (file) {

            },
            onImageLoad:function (url) {
                this.image=url;
            },

            add:function () {

                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }


                this.$v.$touch();
                if (!this.$v.$invalid) {

                    var data=new FormData()
                    data.append('name',this.name);
                    data.append('image',this.image);
                    if(this.parent)
                        data.append('parent_id',this.parent.id);

                    return axios.post('/api/categories', data, config).then(response =>{
                        this.$router.push({name: 'categories_index'});
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
