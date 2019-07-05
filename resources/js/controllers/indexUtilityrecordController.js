if (document.querySelector('#indexUtilityrecordController')) {
  Vue.component('index-utilityrecord', {
    template: '#index-utilityrecord-template',
    data() {
      return {
        list: [],
        search: {
          property_id: '',
          tenant_name: '',
          tenant_phone_number: '',
          tenancy_code: '',
          tenancy_room_name: '',
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
        property_options: [],
        monthyear_options: [],
        zoom_image_url: ''
      }
    },
    mounted() {
      this.fetchTable();
      this.fetchPropertySearchOptions();
      this.fetchMonthyearOptions();
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
          tenant_name: this.search.tenant_name,
          tenant_phone_number: this.search.tenant_phone_number,
          tenancy_code: this.search.tenancy_code,
          tenancy_room_name: this.search.tenancy_room_name,
          monthyear: this.search.monthyear,
          type: this.search.type
        };
        axios.get(
          // subject to change (search list and pagination)
          '/api/utilityrecords/null?page=' + data.page +
          '&perpage=' + data.per_page +
          '&sortkey=' + data.sortkey +
          '&reverse=' + data.reverse +
          '&property_id=' + data.property_id +
          '&tenant_name=' + data.tenant_name +
          '&tenant_phone_number=' + data.tenant_phone_number +
          '&tenancy_room_name=' + data.tenancy_room_name +
          '&tenancy_code=' + data.tenancy_code +
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
      approveSingleEntry(data) {
        axios.post('/api/utilityrecord/verifyreject/' + data.id + '/verified').then((response) => {
          this.searchData();
        })
      },
      rejectSingleEntry(data) {
        axios.post('/api/utilityrecord/verifyreject/' + data.id + '/rejected').then((response) => {
          this.searchData();
        })
      },
      onFilterChanged() {
        this.filterchanged = true;
      },
      fetchPropertySearchOptions() {
        axios.get('/api/properties/all').then((response) => {
          this.property_options = response.data
        });
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
      zoomImage(url) {
        this.zoom_image_url = ''
        this.zoom_image_url = url
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
  new Vue({
    el: '#indexUtilityrecordController',
  });
}