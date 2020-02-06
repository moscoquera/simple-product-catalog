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
                                    <input type="text" class="form-control" v-model="category.name">
                                    <div class="error" v-if="!$v.category.name.required">Name is required</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Category Image:</label>
                                    <br/>
                                    <img :src="category.image_url ? category.image_url : 'https://www.gravatar.com/avatar/default?s=200&r=pg&d=mm'"  class="rounded-circle" style="max-height: 200px"/>
                                    <br/>
                                    <input type="file" class="form-control" @change="processImage($event)" accept="image/*">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Parent Category:</label>
                                    <v-select @search="fetchCategories" v-model="category.parent" :options="categories"></v-select>
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
    const VueUploadComponent = require('vue-upload-component')
    import vSelect from 'vue-select'
    import Vuelidate from 'vuelidate'
    import FormData from 'form-data'
    Vue.use(Vuelidate)
    import { required, minLength,maxLength } from 'vuelidate/lib/validators'



    export default {
        mounted() {
            this.fetchCategories('',function (dum) {});
        },
        data(){
            return {
                category:{
                    image_url:''
                },
                categories:[]
            }
        },
        components: {
            FileUpload: VueUploadComponent,
            vSelect:vSelect,
            Vuelidate:Vuelidate,
            FormData:FormData
        },
        validations:{
            category:{
                name:{
                    required,
                }
            }
        },
        methods: {

            processImage:function ($event) {
                this.category.image=this.someData = $event.target.files[0];
                this.category.image_url = URL.createObjectURL($event.target.files[0]);
            },

            fetchCategories:function (search,loading) {
                loading(true);
                axios.get('/api/categories').then(res => {
                    this.categories=res.data.data;
                    for(let category in this.categories){
                        this.categories[category].label=this.categories[category].name;
                    }
                    loading(false);
                });
            },

            add:function () {

                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }


                this.$v.$touch();
                if (!this.$v.$invalid) {

                    var data=new FormData()
                    data.append('name',this.category.name);
                    data.append('image',this.category.image);
                    if(this.category.parent)
                        data.append('parent_id',this.category.parent.id);

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
