<template>
    <el-tooltip
            class="box-item"
            effect="dark"
            content="Tambah Gambar (CTRL + K)"
            placement="bottom">
            <el-button
                size="small"
                @click="openAddDialog"
                :class="{ 'is-active': editor.isActive('link') }">
                <i class="fa fa-image"></i>
            </el-button>
        </el-tooltip>
        
        <el-dialog v-model="modalOpen" title="Media Manager" class="modal-media-manager" label="Media Manager">
            <el-tabs v-model="activeTab">
                <el-tab-pane label="Upload" name="first">
                    <el-upload
                        class="upload-demo"
                        drag
                        :show-file-list="false"
                        :headers="headers"
                        :on-success="uploadSucess"
                        :action="route('admin.media.store')">
                        <div class="el-upload__text">
                            Drop file here or <em>click to upload</em>
                        </div>
                    </el-upload>
                </el-tab-pane>
                <el-tab-pane label="Media Galeri" name="gallery">
                    <el-row :gutter="20">
                        <el-col :span="18">
                            <div class="gallery">
                                <div class="library__content__images__grid__images" v-if="data.length">
                                    <div v-for="(m, i) in data" :key="i" class="grid-item" @click="selectManual(m)" :class="{'grid-item--active': isActive(m)}">
                                        <div class="grid-item__inner" :style="`background: url('${m.path}'); background-repeat: no-repeat; background-size: cover;`"></div>
                                    </div>
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6" class="border-start">
                            <div class="info" v-if="selected">
                                <div class="thumbnail">
                                    <!-- <img :src="selected.path"/> -->
                                    <el-image style="width: 100%; height: 100px" :src="selected.path" fit="cover" />
                                </div>
                                
                                <div class="fw-medium">{{ selected.filename }}.{{ selected.extension }}</div>
                                <el-button class="w-100" @click.prevent="removeImage">
                                    Hapus
                                </el-button>
                                <hr/>
                                <el-button type="primary" class="w-100" @click.prevent="setImage">
                                    Pilih
                                </el-button>
                            </div>
                        </el-col>
                    </el-row>
                </el-tab-pane>
            </el-tabs>
        </el-dialog>
</template>

<script>
import { Editor } from '@tiptap/core';
export default {
    name : "AddImageButton",
    props : {
        editor: {
            type: Editor,
            required: true,
        },
        buttonIcon: {
            default: '',
            type: String
        },
        placeholder: {
            default: '',
            type: String
        }
    },
    data(){
        return {
            modalOpen: false,
            type : 'link',
            activeTab : 'gallery',
            headers : {
                'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            data : [],
            page : 1,
            total: 0,
            selected: null,
            isLoading : false,
        }
    },
    async created() {
        await this.fetchData();
    },
    methods : {
        async uploadMedia(){
            try {
                this.isLoading = true;
                const response = await axios.post(self.route("admin.media.store"),{
                    params: {
                        file : this.file,
                    }
                });
                if(response.status == 200){
                    this.data = response.data.data;
                    this.page = response.data.current_page;
                    this.total = response.data.total;
                    this.pageSize = response.data.per_page;
                }
                this.isLoading = false;
            } catch (error) {
                console.error(error);
            }
        },
        async fetchData(page) {
            var page = (page == undefined) ? 1 : page;
            try {
                this.isLoading = true;
                const response = await axios.get(self.route("admin.media.data"),{
                    params: {
                        page: page,
                    }
                });
                if(response.status == 200){
                    this.data = response.data.data;
                    this.page = response.data.current_page;
                    // this.total = response.data.total;
                    // this.pageSize = response.data.per_page;
                }
                this.isLoading = false;
            } catch (error) {
                console.error(error);
            }
        },
        selectManual(id){
            this.selected = id;
        },
        uploadSucess(value){
            this.fetchData();
            this.activeTab = 'gallery';
            this.selected = value;
        },
        openAddDialog() {
            this.modalOpen = true;
        },

        closeAddLinkDialog() {
            this.modalOpen = false;
        },
        addLink() {
        if (this.linkAttrs.openInNewTab) {
            this.editor.commands.setLink({
            href: this.linkAttrs.href,
            target: '_blank',
            });
        } else {
            this.editor.commands.setLink({ href: this.linkAttrs.href });
        }
            this.closeAddLinkDialog();
        },
        isActive(m){
            if(this.selected){
                return (this.selected.id == m.id) ? true : false;
            }
            return false;
        },
        setImage(){
            this.editor.commands.setImage({ src: this.selected.path });
            this.modalOpen = false;
        }
    }
}
</script>