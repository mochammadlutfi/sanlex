<template>
    <div>
        <b-modal ref="link-modal" hide-footer size="md" content-class="rounded" rounded body-class="p-0" centered hide-header>
            <div class="block block-rounded  block-transparent mb-0">
                <form @submit.prevent="submit">
                    <div class="block-header">
                        <h3 class="block-title font-weight-bold">Tambahkan Link</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" @click="close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content block-content-sm">
                        <div class="form-group">
                            <input type="text" class="form-control" :class="{ 'is-invalid' : error }" id="field-name" v-model="link" placeholder="Masukan Link Disini">
                            <div v-if="error" class="invalid-feedback">{{ error }}</div>
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

export default {
    name: 'link-modal',
    props: ['value'],
    data() {
        return {
            link: null,
            error : null,
        }
    },
    mounted() {
        if (this.value) {
            this.link = this.value;
        }
    },
    methods: {
        showModal(link) {
            this.$refs['link-modal'].show();
        },
        close() {
            this.$refs['link-modal'].hide();
            this.link = null;
            this.error = null;
        },
        submit() {
            var regex = /(?:https?):\/\/(\w+:?\w*)?(\S+)(:\d+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/;
            if (!regex.test(this.link)) {
                this.error = 'Link Tidak Valid';
            } else {
                this.$emit('onConfirm', this.link);
                this.close();
            }
        },
        hideModal() {
            this.$refs['my-modal'].hide()
        },
        toggleModal() {
            // We pass the ID of the button that we want to return focus to
            // when the modal has hidden
            this.$refs['my-modal'].toggle('#toggle-btn')
        }
    }
};
</script>
