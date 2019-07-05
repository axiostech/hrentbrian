<script>
  import Multiselect from 'vue-multiselect'
  export default {
    components: { Multiselect },
    data () {
      return {
        selected: null,
        options: ['list', 'of', 'options']
      }
    },
    methods: {
      updateSelected (newSelected) {
        this.selected = newSelected
      }
    }
  }
</script>