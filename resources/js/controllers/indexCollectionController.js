if (document.querySelector('#indexCollectionController')) {
  Vue.component('index-collection', {
    template: '#index-collection-template',
    data() {
      return {
        list: [],
        property_options: [],
        unit_options: [],
        monthyear_options: [],
        search: {
          name: '',
          property_id: '',
          unit_id: '',
          monthyear: ''
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
        clearform: {}
      }
    },
    mounted() {
      this.fetchTable();
      this.fetchPropertySearchOptions();
      this.fetchMonthyearSearchOptions();
      this.search.monthyear = moment().format('MM-YYYY');
    },
    methods: {
      fetchTable() {
        this.searching = true;
        let data = {
          // subject to change (search list and pagination)
          paginate: this.pagination.per_page,
          page: this.pagination.current_page,
          sortkey: this.sortkey,
          reverse: this.reverse,
          per_page: this.selected_page,
          name: this.search.name,
          property_id: this.search.property_id,
          unit_id: this.search.unit_id,
          monthyear: this.search.monthyear
        };
        axios.get(
          // subject to change (search list and pagination)
          '/api/collections?page=' + data.page +
          '&perpage=' + data.per_page +
          '&sortkey=' + data.sortkey +
          '&reverse=' + data.reverse +
          '&name=' + data.name +
          '&property_id=' + data.property_id +
          '&unit_id=' + data.unit_id +
          '&monthyear=' + data.monthyear
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
      createSingleEntry() {
        this.clearform = {}
        this.formdata = '';
      },
      editSingleEntry(data) {
        this.clearform = {}
        this.formdata = '';
        this.formdata = data
      },
      fetchUnitSearchOptions() {
        this.search.unit_id = ''
        this.unit_options = []
        if (this.search.property_id) {
          axios.get('/api/units/property/' + this.search.property_id).then((response) => {
            this.unit_options = response.data
          });
        }
      },
      deactivateSingleEntry(data) {
        var approval = confirm('Are you sure to deactivate ' + data.name + '?')
        if (approval) {
          axios.delete('/api/tenant/' + data.id + '/deactivate').then((response) => {
            this.searchData();
          });
        } else {
          return false;
        }
      },
      onFilterChanged() {
        this.filterchanged = true;
      },
      fetchMonthyearSearchOptions() {
        this.monthyear_options.push({
          id: moment().add(1, 'months').format('MM-YYYY'),
          name: moment().add(1, 'months').format('MMMM YYYY')
        })
        this.monthyear_options.push({
          id: moment().format('MM-YYYY'),
          name: moment().format('MMMM YYYY')
        })
        // previous months
        for(var i = 1; i <= 6; i++) {
          var obj = {};
          obj['id'] = moment().subtract(i, 'months').format('MM-YYYY');
          obj['name'] = moment().subtract(i, 'months').format('MMMM YYYY');
          this.monthyear_options.push(obj);
        }
      },
    },
    watch: {
      'selected_page'(val) {
        this.selected_page = val;
        this.pagination.current_page = 1;
        this.fetchTable();
      }
    }
  });
/*
  Vue.component('form-tenant', {
    template: '#form-tenant-template',
    props: ['data', 'clearform'],
    data() {
      return {
        form: {
          id: '',
          name: '',
          phone_number: '',
          email: '',
          property_id: '',
          unit_id: '',
          tenancy_id: '',
          idtype_id: '',
          id_value: ''
        },
        idtype_options: [],
        formErrors: {}
      }
    },
    mounted() {
      this.fetchIdtypeSearchOptions();
    },
    methods: {
      onSubmit() {
        axios.post('/api/tenant/store-update', this.form)
          .then((response) => {
            $('.modal').modal('hide');
            for (var key in this.form) {
              this.form[key] = null;
            }
            this.$emit('updatetable')
            flash('Entry has successfully created or updated', 'success');
          })
          .catch((error) => {
            this.formErrors = error.response.data.errors
          });
      },
      fetchIdtypeSearchOptions() {
        axios.get('/api/idtypes/all').then((response) => {
          this.idtype_options = response.data
        });
      }
    },
    watch: {
      'data'(val) {
        for (var key in this.form) {
          this.form[key] = this.data[key];
        }
      },
      'clearform'(val) {
        if (val) {
          this.formErrors = val
        }
      }
    }
  }); */

  new Vue({
    el: '#indexCollectionController',
  });
}