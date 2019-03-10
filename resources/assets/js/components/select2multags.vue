<template>
  <select>
    <slot></slot>
  </select>
</template>

<script>
    export default {
      props: ['options', 'value'],
      template: '#select2-template',
      mounted: function () {
        var vm = this
        $(this.$el)
          // init select2
          .select2({ data: this.options, multiple: true, tags: true })
          .val(this.value)
          .trigger('change')
          // emit event on change.
          .on('change', function () {
            vm.$emit('input', $(this).val())
          })
      },
      watch: {
        value: function (value) {
          //revisar que los valores de input sean diferentes de los ya en en select
          if ([...value].sort().join(",") !== [...$(this.$el).val()].sort().join(","))
          $(this.$el).val(value).trigger('change')
        },
        options: function (options) {
          // update options
          $(this.$el).empty().select2({ data: options, multiple: true, tags: true })
        }
      },
      destroyed: function () {
        $(this.$el).off().select2('destroy')
      }
    }
</script>
