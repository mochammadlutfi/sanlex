<template>
    <el-tooltip
            class="box-item"
            effect="dark"
            content="Tambah Link (CTRL + K)"
            placement="bottom">
            <el-button
                size="small"
                @click="openAddLinkDialog"
                :class="{ 'is-active': editor.isActive('link') }">
                <i class="fa fa-link"></i>
            </el-button>
        </el-tooltip>
        <el-dialog
            v-model="addLinkDialogVisible"
            title="Tambah Link"
            :append-to-body="true"
            width="400px"
            modal-class="p-2"
            class="rounded-3">
            <el-form :model="linkAttrs" label-position="top">
                <el-form-item label="Link/URL" prop="href">
                    <el-input
                        v-model="linkAttrs.href"
                        autocomplete="off"
                        :placeholder="placeholder"/>
                </el-form-item>

                <el-form-item prop="openInNewTab">
                    <el-checkbox v-model="linkAttrs.openInNewTab">
                        Buka link di tab baru
                    </el-checkbox>
                </el-form-item>
            </el-form>

            <template #footer>
                <el-button @click="closeAddLinkDialog">
                    Batal
                </el-button>
                <el-button type="primary" @click="addLink">
                    Simpan
                </el-button>
        </template>
        </el-dialog>
</template>

<script>
import { Editor } from '@tiptap/core';
export default {
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
            linkAttrs: {
                href: '',
                openInNewTab: true,
            },
            addLinkDialogVisible: false,
        }
    },
    methods : {
        openAddLinkDialog() {
            this.addLinkDialogVisible = true;
        },

        closeAddLinkDialog() {
            this.addLinkDialogVisible = false;
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
    }
}
</script>