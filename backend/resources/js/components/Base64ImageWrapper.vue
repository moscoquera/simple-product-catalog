<script>
    import VueBase64FileUpload from "vue-base64-file-upload";

    export default {
        components: {
            "vue-base64-file-upload": VueBase64FileUpload
        },

        data() {
            return {
                customImageMaxSize: 3 // megabytes
            };
        },

        props: {
            onLoadCallback: { type: Function },
            id: { type: Number },
            default_img:{type:String}
        },

        methods: {
            onFile(file) {
                // console.log(file); // file object
            },

            onLoad(dataUri) {
                // console.log(dataUri); // data-uri string
                this.onLoadCallback(dataUri, this.id);
            },

            onSizeExceeded(size) {
                alert(
                    `Image ${size}Mb size exceeds limits of ${this.customImageMaxSize}Mb!`
                );
            }
        },

        template: `
    <div class="container">
      <vue-base64-file-upload
        class="v1"
        accept="image/png,image/jpeg"
        image-class="v1-image"
        input-class="v1-input"
        v-bind:default-preview="default_img"
        :max-size="customImageMaxSize"
        @size-exceeded="onSizeExceeded"
        @file="onFile"
        @load="onLoad" />
    </div>
  `
    };
</script>
