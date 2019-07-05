if (document.querySelector('#indexCheckoutController')) {
    Vue.component('index-insurance', {
      template: '#index-checkout-template',
      
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
          checkout_arry:[],
          total_amount:0,
          processingmsg: false
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
            '/api/getcheckout?page=' + data.page +
            '&perpage=' + data.per_page +
            '&sortkey=' + data.sortkey +
            '&reverse=' + data.reverse +
            '&property_id=' + data.property_id +
            '&unit_id=' + data.unit_id
          ).then((response) => {
            
             // console.log(response.data);
            const result = response.data.checkoutlist;
            const plans = response.data.plans;
            const total_amount = response.data.total_amount;
            const tax_amount  = response.data.tax_amount;
           
            //console.log(plans);
            if (result) {
              this.list = result;
              console.log('---------------->');
              console.log(result);
              this.plans =plans;
              console.log(this.plans);
              this.total_amount = total_amount;
              this.tax_amount = tax_amount;
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
        createBillForInsurance: function() {
          //Disable our button and show msg
          this.processingmsg = true;
          $('#createinsurance').attr("disabled", true);
         axios.post('/api/insurance/create-bill',  this.list ).then((response) => {
            
            // this.planShowing = true;
           // alert(response.data.bill_id);
            if(response.data.bill_id){
              this.processingmsg = false;
              $('#createinsurance').attr("disabled", false);
              window.open(response.data.bill_url, '_self');
            }else{
              this.processingmsg = false;
              $('#createinsurance').attr("disabled", false);
              alert('Something wnents wrong!');
            }
           
            //console.log(response.data.bill_url);
           
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
      el: '#indexCheckoutController',
    });
  }