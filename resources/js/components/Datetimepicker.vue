<template>
  <input type="text" class="form-control">
</template>

<script>
  export default {
    props: ['options', 'value'],
    mounted: function () {
      var self = this
      $(this.$el)
        .datepicker({
          format: "yyyy-mm-dd",
          weekStart: 1,
          autoclose: true,
          clearBtn: true,
          todayHighlight: true,
          orientation: 'bottom auto'
        })
        .on('changeDate', function () {
          self.$emit('input', this.value)
        })
    },
  }
</script>
