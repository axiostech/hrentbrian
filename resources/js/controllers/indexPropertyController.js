if (document.querySelector('#indexPropertyController')) {
  Vue.component('index-property', {
    template: '#index-property-template',
    data() {
      return {
        list: [],
        propertytype_options: [],
        unit_options: [],
        search: {
          name: '',
          state: '',
          city: '',
          postcode: '',
          type_id: ''
        },
        searching: false,
        sortkey: '',
        reverse: false,
        selected_page: '100',
        pagination: {
          total: 0,
          from: 1,
          per_page: 1,
          current_page: 1,
          last_page: 0,
          to: 5
        },
        formdata: {},
        filterchanged: false
      }
    },
    mounted() {
      this.fetchTable();
      this.fetchPropertytypeSearchOptions();
    },
    methods: {
      fetchTable() {
        this.searching = true;
        let data = {
          paginate: this.pagination.per_page,
          page: this.pagination.current_page,
          sortkey: this.sortkey,
          reverse: this.reverse,
          per_page: this.selected_page,
          name: this.search.name,
          state: this.search.state,
          city: this.search.city,
          type_id: this.search.type_id
        };
        axios.get(
          // subject to change (search list and pagination)
          '/api/properties?page=' + data.page +
          '&perpage=' + data.per_page +
          '&sortkey=' + data.sortkey +
          '&reverse=' + data.reverse +
          '&name=' + data.name +
          '&state=' + data.state +
          '&city=' + data.city +
          '&type_id=' + data.type_id
        ).then((response) => {
          const result = response.data;
          if (result) {
            this.list = result.data;
            this.pagination = {
              total: result.total,
              from: result.from,
              to: result.to,
              per_page: result.per_page,
              current_page: result.current_page,
              last_page: result.last_page,
            }
          }
        });
        this.searching = false;
      },
      searchData() {
        this.pagination.current_page = 1;
        this.fetchTable();
        this.filterchanged = false;
      },
      sortBy(sortkey) {
        this.pagination.current_page = 1;
        this.reverse = (this.sortkey == sortkey) ? !this.reverse : false;
        this.sortkey = sortkey;
        this.fetchTable();
      },
      fetchPropertytypeSearchOptions() {
        axios.get('/api/propertytypes/all').then((response) => {
          this.propertytype_options = response.data
        });
      },
      createSingleProperty() {
        this.formdata = '';
      },
      editSingleProperty(data) {
        this.formdata = '';
        this.formdata = data
      },
      removeSingleProperty(data) {
        var approval = confirm('Are you sure to delete ' + data.name + '?')
        if(approval) {
          axios.delete('/api/property/' + data.id + '/delete').then((response) => {
            this.searchData();
          });
        }else {
          return false;
        }
      },
      onFilterChanged() {
        this.filterchanged = true;
      }
    },
    watch: {
      'selected_page'(val) {
        this.selected_page = val;
        this.pagination.current_page = 1;
        this.fetchTable();
      }
    }
  });

  Vue.component('form-property', {
    template: '#form-property-template',
    props: ['data'],
    data() {
      return {
        form: {
          id: '',
          name: '',
          ptd_code: '',
          address: '',
          postcode: '',
          city: '',
          state: '',
          attn_name: '',
          attn_phone_number: '',
          type_id: ''
        },
        propertytype_options: []
      }
    },
    mounted() {
      this.fetchPropertytypeSearchOptions();
    },
    methods: {
      onSubmit() {
        axios.post('/api/property/store-update', this.form).then((response) => {
          $('.modal').modal('hide');
          for (var key in this.form) {
            this.form[key] = null;
          }
          this.$emit('updatetable')
        });
      },
      fetchPropertytypeSearchOptions() {
        axios.get('/api/propertytypes/all').then((response) => {
          this.propertytype_options = response.data
        });
      }
    },
    watch: {
      'data'(val) {
        for (var key in this.form) {
          this.form[key] = this.data[key];
        }
      }
    }
  });
  new Vue({
    el: '#indexPropertyController',
  });
}