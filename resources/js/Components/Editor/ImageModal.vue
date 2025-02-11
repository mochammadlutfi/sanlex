<template>
    <div>
        <b-modal ref="image-modal" hide-footer size="md" content-class="rounded" rounded body-class="p-0" centered hide-header>
            <div class="block block-rounded  block-transparent mb-0">
                <form @submit.prevent="submit">
                    <div class="block-header pb-0">
                        <h3 class="block-title font-weight-bold">Tambah Gambar</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" @click="close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <ul class="nav nav-fill nav-tabs nav-tabs-alt" data-toggle="tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" :class="{ 'active' : type == 'link' }" href="#" @click="changeType('link')">Link</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" :class="{ 'active' : type == 'upload' }" href="#"  @click="changeType('upload')">Upload</a>
                        </li>
                    </ul>
                    <div class="block-content">
                        <div class="form-group" v-if="type == 'link'">
                            <input type="text" class="form-control" :class="{ 'is-invalid' : error }" id="field-name" v-model="link" placeholder="Masukan Link Disini">
                            <div v-if="error" class="invalid-feedback">{{ error }}</div>
                        </div>
                        <div class="form-group" v-else>
                           <b-form-file
                            v-model="srcImage"
                            placeholder="Upload Gambar"
                            drop-placeholder="Drop file here..."
                            accept="image/jpeg, image/png, image/gif"
                            @change="validateFiles"
                            ></b-form-file>
                            <div v-if="error" class="text-danger text-sm">{{ error }}</div>
                        </div>
                    </div> 
                    <div class="block-content block-content-full block-content-sm text-right">
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="si si-close"></i>
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="si si-check"></i>
                            Tambahkan
                        </button>
                    </div>
                </form>
            </div>
        </b-modal>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    name: 'image-modal',
    props: ['value'],
    data() {
        return {
            link: '',
            error : null,
            type : 'link',
            srcImage : null,
            content_id : null,
        }
    },
    mounted() {
        if (this.value) {
            this.link = this.value;
        }
    },
    methods: {
        showModal(editor, content_id) {
            this.content_id = content_id;
            this.$refs['image-modal'].show();
        },
        changeType(t){
            this.type = t;
            this.error = null;
            this.srcImage = null;
        },
        close() {
            this.$refs['image-modal'].hide();
            this.link = null;
            this.error = null;
        },
        validateFiles(e){
            const file = e.target.files[0];
            const fileSize = Math.round((file.size / 1024));
            if (fileSize > 2000) {
                this.srcImage = null;
                this.error = 'Ukuran Gambar Terlalu Besar, Maksimal 2MB';
            }else{
                this.error = null;
            }
        },
        submit() {
            if(this.type == 'link'){
                var regex = /(?:https?):\/\/(\w+:?\w*)?(\S+)(:\d+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/;
                if (!regex.test(this.link)) {
                    this.error = 'Link Tidak Valid';
                } else {
                    this.$emit('onConfirm', { url : this.link, source : 'lk'});
                    this.close();
                }
            }else{
                const formData = new FormData();
                formData.append('image', this.srcImage);
                formData.append('content_id', this.content_id);
                axios.post(this.route('media.upload'), formData, {
                }).then((response) => {
                    const data = response.data;
                    this.$emit('onConfirm', { url : data.image, source : 'up'});
                    this.close();
                });
            }
        },
        hideModal() {
            this.$refs['image-modal'].hide()
        },
        toggleModal() {
            // We pass the ID of the button that we want to return focus to
            // when the modal has hidden
            this.$refs['image-modal'].toggle('#toggle-btn')
        }
    }
};
</script>
