if (document.querySelector('#indexInsuranceController')) {
  Vue.component('index-insurance', {
    template: '#index-insurance-template',
    
    data() {
      return {
        list: [],
        plans:[],
        search: {
          name: '',
          coverages: '',
          remarks: '',
          status: '1',
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
        planShowing: false,
        planselected: [],
        showplanselected:{
          plantotal:0,
          unit_id:'',
          plan_id:'',
        },
        checkout_arry:[]
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
          property_id: this.search.property_id,
          unit_id: this.search.unit_id
        };
        axios.get(
          // subject to change (search list and pagination)
          '/api/insurances?page=' + data.page +
          '&perpage=' + data.per_page +
          '&sortkey=' + data.sortkey +
          '&reverse=' + data.reverse +
          '&property_id=' + data.property_id +
          '&unit_id=' + data.unit_id
        ).then((response) => {
          const result = response.data.insurances;
          const plans = response.data.plans;
         
          if (result) {
            this.list = result.data;
            console.log(result.data);
            this.plans =plans;
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
      createSingleInsurance() {
        this.formdata = '';
      },
      editSingleInsurance(data) {
        this.formdata = '';
        this.formdata = data
      },
      deactivateSingleInsurance(data) {
        var approval = confirm('Are you sure to deactivate ' + data.name + '?')
        if (approval) {
          axios.delete('/api/insurance/' + data.id + '/deactivate').then((response) => {
            this.searchData();
          });
        } else {
          return false;
        }
      },
      onFilterChanged() {
        this.filterchanged = true;
      },

      //function get the selected plan values
      getplanonchange: function(event) {
       var uid =  $(this).find(':selected').data('id');
      // alert(event.target.options[event.target.options.selectedIndex].dataset.foo);
       //console.log(e.target.options[e.target.options.selectedIndex].dataset.foo)
        console.log(event.target.value);
        var plan_price = event.target.value.split(' ');

        var unit_arr = plan_price[1].split('RM_unit_');
        var pal_sp = plan_price[1].split('_plan_id_');
        var unit_ar = unit_arr[1].split('_plan_id_');

        var plan_selected = {
          unit_number:unit_ar[0],
          plan_amount: plan_price[0],
          plan_id : pal_sp[1]
        };

        console.log(plan_selected);
        this.checkout_arry.push(plan_selected);
        //call and save to session
        axios.post('/api/insurance/store-update', this.checkout_arry).then((response) => {
          // $('.modal').modal('hide');
          //console.log(response.data.total);
          this.planShowing = true;
          this.showplanselected.plantotal =response.data.total;
          // this.showplanselected.plantotal =response.total;
        });

              
      },
      //
      onSubmit() {
       
        axios.post('/api/insurance/store-update', this.form).then((response) => {
          $('.modal').modal('hide');
          for (var key in this.form) {
            this.form[key] = null;
          }
          this.$emit('updatetable')
        });
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
 

  Vue.component('form-insurance', {
    template: '#form-insurance-template',
    props: ['data'],
    data() {
      return {
        form: {
          id: '',
          name: '',
          coverages: '',
          remarks: ''
        }
      }
    },
    methods: {
      onSubmit() {
        axios.post('/api/insurance/store-update', this.form).then((response) => {
          $('.modal').modal('hide');
          for (var key in this.form) {
            this.form[key] = null;
          }
          this.$emit('updatetable')
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
    el: '#indexInsuranceController',
  });
}