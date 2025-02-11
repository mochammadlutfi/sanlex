<template>
    <el-popover
    placement="top"
    trigger="click"
    popper-class="el-tiptap-popper"
    ref="popoverRef"
  >
    <div class="el-tiptap-popper__menu">
      <div
        v-for="display in displayCollection"
        :key="display"
        :class="{
          'el-tiptap-popper__menu__item--active': display === currDisplay,
        }"
        class="el-tiptap-popper__menu__item"
        @mousedown="hidePopover"
        @click="updateAttrs({ display })"
      >
        <span>{{
          t(`editor.extensions.Image.buttons.display.${display}`)
        }}</span>
      </div>
    </div>

    <template #reference>
      <span>
        <command-button
          :enable-tooltip="enableTooltip"
          :tooltip="t('editor.extensions.Image.buttons.display.tooltip')"
          icon="image-align"
        />
      </span>
    </template>
  </el-popover>
</template>

<script>
import { ImageDisplay } from '@/Components/Editor/utlis/Image';
export default {
    name: 'ImageDisplayCommandButton',
    props: {
        node: nodeViewProps['node'],
        updateAttrs: nodeViewProps['updateAttributes'],
    },
    setup() {
        const t = inject('t');
        const enableTooltip = inject('enableTooltip', true);

        return { t, enableTooltip };
    },
    methods: {
        hidePopover() {
        this.$refs.popoverRef?.hide();
        },
    },
}
</script>