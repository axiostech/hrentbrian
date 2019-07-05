if (document.querySelector('#indexUnitController')) {
  Vue.component('index-unit', {
    template: '#index-unit-template',
    data() {
      return {
        list: [],
        property_options: [],
        unit_options: [],
        search: {
          property_id: '',
          unit_id: ''
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
        filterchanged: false,
        current_unit_id:null
      }
    },
    mounted() {
      this.fetchTable();
      this.fetchPropertySearchOptions();
      this.fetchUnitSearchOptions();
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
          property_id: this.search.property_id,
          unit_id: this.search.unit_id
        };
        axios.get(
          // subject to change (search list and pagination)
          '/api/units?page=' + data.page +
          '&perpage=' + data.per_page +
          '&sortkey=' + data.sortkey +
          '&reverse=' + data.reverse +
          '&property_id=' + data.property_id +
          '&unit_id=' + data.unit_id
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
      fetchPropertySearchOptions() {
        axios.get('/api/properties/all').then((response) => {
          this.property_options = response.data
        });
      },
      fetchUnitSearchOptions() {
        axios.get('/api/units/all').then((response) => {
          this.unit_options = response.data
        });
      },
      createSingleUnit() {
        this.formdata = '';
      },
      editSingleUnit(data) {
        this.formdata = '';
        this.formdata = data
      },
      editServices(data) {
        this.formdata = '';
        this.formdata = data;
      },
      removeSingleUnit(data) {
        var approval = confirm('Are you sure to delete ' + data.name + '?')
        if (approval) {
          axios.delete('/api/unit/' + data.id + '/delete').then((response) => {
            this.searchData();
          });
        } else {
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

  Vue.component('form-unit', {
    template: '#form-unit-template',
    props: ['data'],
    data() {
      console.log('sdsdsdsdsds')
      return {
        form: {
          id: '',
          block_number: '',
          unit_number: '',
          address: '',
          remarks: '',
          squarefeet: '',
          purchase_price: '',
          bedbath_room: '',
          purchased_at: ''
        },
        property_options: []
      }
    },
    mounted() {
      this.fetchPropertySearchOptions();
    },
    methods: {
      onSubmit() {
        axios.post('/api/unit/store-update', this.form).then((response) => {
          $('.modal').modal('hide');
          for (var key in this.form) {
            this.form[key] = null;
          }
          this.$emit('updatetable')
        });
      },
      fetchPropertySearchOptions() {
        axios.get('/api/properties/all').then((response) => {
          this.property_options = response.data
        });
      },
    },
    watch: {
      'data'(val) {
        for (var key in this.form) {
          this.form[key] = this.data[key];
        }
      }
    }
  });

  Vue.component('form-service', {
    template: '#form-service-template',
    props: ['data'],
    data() {
      return {
        service: {
          unit_id: '',
          services:{
            electricity:{
              operator_code:'',
              account_number:[]
            },
            water:{
              operator_code:'',
              account_number:[]
            },
            broadband:{
              operator_code:'',
              account_number:[]
            }
          }
        },
        operators:[]
      }
    },
    mounted() {
      this.fetchUnitServiceAccounts();
    },
    methods: {
      onSubmit() {
        axios.post('/api/unit/service-update', this.form).then((response) => {
          $('.modal').modal('hide');
          for (var key in this.form) {
            this.form[key] = null;
          }
          this.$emit('updatetable')
        });
      },
      fetchUnitServiceAccounts() {
        axios.get('/api/unit/service-accounts').then((response) => {
          this.operators = response.data
        });
      },
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
    el: '#indexUnitController',
  });
}