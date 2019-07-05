if (document.querySelector('#tenantUtilityrecordController')) {
  Vue.component('index-tenant-utilityrecord', {
    template: '#index-tenant-utilityrecord-template',
    data() {
      return {
        list: [],
        search: {
          monthyear: '',
          type: ''
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
        clearform: {},
        monthyear_options: [],
        tenancy_id: $('#tenancy_id').val()
      }
    },
    mounted() {
      this.fetchMonthyearOptions();
      this.fetchTable();
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
          monthyear: this.search.monthyear,
          type: this.search.type,
          // tenancy_id: $('#tenancy_id').val()
        };
        axios.get(
          // subject to change (search list and pagination)
          '/api/utilityrecords/' + this.tenancy_id + '?page=' + data.page +
          // '/api/profiles?page='
          '&perpage=' + data.per_page +
          '&sortkey=' + data.sortkey +
          '&reverse=' + data.reverse +
          '&monthyear=' + data.monthyear +
          '&type=' + data.type
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
      createSingleEntry() {
        this.clearform = {}
        this.formdata = '';
      },
      editSingleEntry(data) {
        this.clearform = {}
        this.formdata = '';
        this.formdata = data
      },
      removeSingleEntry(data) {
        var approval = confirm('Are you sure to delete ' + data.name + '?')
        if (approval) {
          axios.delete('/api/profile/' + data.id + '/deactivate').then((response) => {
            this.searchData();
          });
        } else {
          return false;
        }
      },
      onFilterChanged() {
        this.filterchanged = true;
      },
      fetchMonthyearOptions() {
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
      requestRemoveSingleEntry(id) {
        axios.delete('/api/utilityrecord/requestremove/' + id).then((response) => {
          this.fetchTable();
        });
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

  Vue.component('form-tenant-utilityrecord', {
    template: '#form-tenant-utilityrecord-template',
    props: ['data', 'clearform', 'tenancyid'],
    data() {
      return {
        form: {
          id: '',
          monthyear: '',
          type: '',
          reading: '',
          amount: '',
          remarks: '',
          image_url: '',
          status: '',
        },
        formData: new FormData(),
        formErrors: {},
        monthyear_options: [],
        file_name: ''
      }
    },
    mounted() {
      this.fetchMonthyearOptions();
      this.formData.append('tenancy_id', this.tenancyid);
    },
    methods: {
      onSubmit() {
        for (var key in this.form) {
          if(this.form[key]) {
            this.formData.append(key, this.form[key]);
          }
        }

        axios.post('/api/utilityrecord/store-update', this.formData)
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
          // console.log(JSON.parse(JSON.stringify(this.formErrors)))
        });
      },
      onFileChange(e) {
        var file = e.target.files[0];
        this.file_name = file.name;
        this.formData.append('image', file);
      },
      fetchMonthyearOptions() {
        this.monthyear_options.push({
          id: moment().add(1, 'months').format('MM-YY'),
          name: moment().add(1, 'months').format('MMMM YYYY')
        })
        this.monthyear_options.push({
          id: moment().format('MM-YY'),
          name: moment().format('MMMM YYYY')
        })
        // previous months
        for (var i = 1; i <= 6; i++) {
          var obj = {};
          obj['id'] = moment().subtract(i, 'months').format('MM-YY');
          obj['name'] = moment().subtract(i, 'months').format('MMMM YYYY');
          this.monthyear_options.push(obj);
        }
      },
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
  });
  new Vue({
    el: '#tenantUtilityrecordController',
  });
}