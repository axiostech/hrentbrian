if (document.querySelector('#indexUserController')) {
  Vue.component('index-user', {
    template: '#index-user-template',
    data() {
      return {
        list: [],
        search: {
          name: '',
          email: '',
          phone_number: ''
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
          email: this.search.email,
          phone_number: this.search.phone_number
        };
        axios.get(
          // subject to change (search list and pagination)
          '/api/users?page=' + data.page +
          '&perpage=' + data.per_page +
          '&sortkey=' + data.sortkey +
          '&reverse=' + data.reverse +
          '&name=' + data.name +
          '&email=' + data.email +
          '&phone_number=' + data.phone_number
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
        this.formdata = ''
      },
      editSingleEntry(data) {
        this.clearform = {}
        this.formdata = ''
        this.formdata = data
      },
      deactivateSingleEntry(data) {
        var approval = confirm('Are you sure to delete ' + data.name + '?')
        if (approval) {
          axios.delete('/api/user/' + data.id + '/delete').then((response) => {
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

  Vue.component('form-user', {
    template: '#form-user-template',
    props: ['data', 'clearform'],
    data() {
      return {
        form: {
          id: '',
          name: '',
          email: '',
          phone_number: '',
          password: '',
          password_confirmation: ''
        },
        formErrors: {}
      }
    },
    methods: {
      onSubmit() {
        axios.post('/api/user/store-update', this.form)
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
      }
    },
    watch: {
      'data'(val) {
        for (var key in this.form) {
          this.form[key] = this.data[key];
        }
      },
      'clearform'(val) {
        if(val) {
          this.formErrors = val
        }
      }
    }
  });
  new Vue({
    el: '#indexUserController',
  });
}