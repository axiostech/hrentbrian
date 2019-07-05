if (document.querySelector('#indexProfileController')) {
  Vue.component('index-profile', {
    template: '#index-profile-template',
    data() {
      return {
        list: [],
        search: {
          name: '',
          attn_name: '',
          attn_phone_number: '',
          domain_name: ''
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
          attn_name: this.search.attn_name,
          attn_phone_number: this.search.attn_phone_number,
          domain_name: this.search.domain_name,
          email: this.search.email
        };
        axios.get(
          // subject to change (search list and pagination)
          '/api/profiles?page=' + data.page +
          '&perpage=' + data.per_page +
          '&sortkey=' + data.sortkey +
          '&reverse=' + data.reverse +
          '&name=' + data.name +
          '&attn_name=' + data.attn_name +
          '&attn_phone_number=' + data.attn_phone_number +
          '&domain_name=' + data.domain_name +
          '&email=' + data.email
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

  Vue.component('form-profile', {
    template: '#form-profile-template',
    props: ['data', 'clearform'],
    data() {
      return {
        form: {
          id: '',
          name: '',
          roc: '',
          address: '',
          postcode: '',
          city: '',
          state: '',
          attn_name: '',
          attn_phone_number: '',
          domain_name: '',
          email: '',
          prefix: '',
          user_id: '',
          logo_url: '',
        },
        formData: new FormData(),
        formErrors: {},
      }
    },
    methods: {
      onSubmit() {
        for (var key in this.form) {
          if(this.form[key]) {
            this.formData.append(key, this.form[key]);
          }
        }

        axios.post('/api/profile/store-update', this.formData)
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
      onFileChange(e) {
        this.formData.append('image', e.target.files[0]);
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
  });
  new Vue({
    el: '#indexProfileController',
  });
}