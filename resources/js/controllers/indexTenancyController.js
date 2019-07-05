if (document.querySelector('#indexTenancyController')) {
  Vue.component('index-tenancy', {
    template: '#index-tenancy-template',
    data() {
      return {
        list: [],
        property_options: [],
        propertytype_options: [],
        unit_options: [],
        search: {
          property_id: '',
          unit_id: '',
          tenant_id: '',
          tenant_name: '',
          tenant_phone_number: '',
          datefrom: '',
          dateto: '',
          code: ''
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
        collection_message:false,
        editform: this.getEditFormData(),
        is_file_selected: false,
        formData: new FormData()
      }
    },
    mounted() {
      this.fetchTable();
      this.fetchPropertySearchOptions();
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
          property_id: this.search.property_id,
          unit_id: this.search.unit_id,
          tenant_id: this.search.tenant_id,
          tenant_name: this.search.tenant_name,
          tenant_phone_number: this.search.tenant_phone_number,
          datefrom: this.search.datefrom,
          dateto: this.search.dateto,
          code: this.search.code
        };
        axios.get(
          // subject to change (search list and pagination)
          '/api/tenancies?page=' + data.page +
          '&perpage=' + data.per_page +
          '&sortkey=' + data.sortkey +
          '&reverse=' + data.reverse +
          '&property_id=' + data.property_id +
          '&unit_id=' + data.unit_id +
          '&tenant_id=' + data.tenant_id +
          '&tenant_name=' + data.tenant_name +
          '&tenant_phone_number=' + data.tenant_phone_number +
          '&datefrom=' + data.datefrom +
          '&dateto=' + data.dateto +
          '&code=' + data.code
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
      createSingleTenancy() {
        this.formdata = '';
      },
      editSingleTenancy(tenancy_id) {
        this.getEditFormData()
        axios.get('/api/tenancy/single/' + tenancy_id).then((response) => {
          this.editform = response.data
        });
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
      removeSingleTenancy(data) {
        var approval = confirm('Are you sure to delete ' + data.name + '?')
        if (approval) {
          axios.delete('/api/tenancy/' + data.id + '/delete').then((response) => {
            this.searchData();
          });
        } else {
          return false;
        }
      },
      onFilterChanged() {
        this.filterchanged = true;
      },
      onDateChanged(value) {
        this.search[value] = moment(this.search[value]).format('YYYY-MM-DD')
      },
      makeCollection(data) {
        axios.post('/arc/collection', {tenancy_id:data.tenancy_id}).then((response) => {
          this.collection_message = true;
          setTimeout(function(){
            this.collection_message = false;
          },3000)
        });
      },
      registerARC(tenancy_id, channel) {
        axios.get('/api/arc/create-send/' + tenancy_id + '/' + channel).then((response) => {
          this.fetchTable();
          flash('ARC form has send to tenant (Email and SMS).', 'success');
        });
      },
      sendArcWhatsapp(tenancy_id) {
        axios.get('/api/arc/create-send/' + tenancy_id + '/whatsapp').then((response) => {
          let phone_number = response.data.phone_number_noplus;
          let message = response.data.message;
          this.fetchWhatsapp(phone_number, message);
          flash('ARC form has sent to tenant (Whatsapp).', 'success');
        });
      },
      sendUtilityRecordWhatsapp(tenancy_id) {
        axios.get('/api/utilityrecord/whatsapp/' + tenancy_id).then((response) => {
          let phone_number = response.data.phone_number_noplus;
          let message = response.data.message;
          this.fetchWhatsapp(phone_number, message);
          flash('Utility Record form has sent to tenant (Whatsapp).', 'success');
        });
      },
      fetchWhatsapp(phone_number, message) {
        var isTouchDevice = function () { return 'ontouchstart' in window || 'onmsgesturechange' in window; };
        var whatsappapi = 'https://api.whatsapp.com/send?phone=' + phone_number + '&text=' + encodeURI(message);

        if (!isTouchDevice()) {
          window.open(whatsappapi, '_blank');
        } else {
          whatsapplink = document.createElement('a');
          whatsapplink.style.display = "none";
          whatsapplink.setAttribute('target', '_blank');
          whatsapplink.setAttribute('href', 'whatsapp://send?phone=' + phone_number + '&text=' + encodeURI(message));
          whatsapplink.click();
        }
      },
/*       onAgreementUpload(event, tenancy_id) {
        let input = event.target;
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          // let vm = this;
          reader.onload = e => {
            // this.previewImageUrl = e.target.result;
            // console.log(this.previewImageUrl);
            this.agreement = e.target.result;
            console.log(this.agreement);

            axios.post('/api/tenancy/' + tenancy_id + '/upload/agreement', {agreement: this.agreement}).then((response) => {
              this.editSingleTenancy(tenancy_id);
            });
          }
          reader.readAsDataURL(input.files[0]);

        }
      }, */
      onAgreementChosen(e) {
        this.formData.append('agreement', e.target.files[0]);
        this.is_file_selected = true
      },
      onAgreementUpload(tenancy_id) {
        // axios.defaults.headers.post['Content-Type'] = 'multipart/form-data';
        axios.post('/api/tenancy/' + tenancy_id + '/upload/agreement', this.formData).then((response) => {
          this.editSingleTenancy(tenancy_id);
        });
      },
      getFileExtensionFilter(agreement_url) {
        var extension_name = agreement_url.split('.').pop()
        var type = ''

        switch(extension_name) {
          case 'pdf':
            type = 'pdf';
            break;
          case 'jpg':
          case 'jpeg':
          case 'png':
          case 'gif':
            type = 'img';
            break;
          case 'docx':
            type = 'msdoc';
            break;
          default:
            type = 'others';
        }

        return type;
      },
      removeAgreementUrl(tenancy_id) {
        axios.delete('/api/tenancy/' + tenancy_id + '/agreement').then((response) => {
          this.editSingleTenancy(tenancy_id)
        });
      },
      getEditFormData() {
        return {
          tenancy_code: '',
          tenancy_id: '',
          tenancy_date: '',
          tenancy_durationmonth: '',
          tenancy_datefrom: '',
          tenancy_dateto: '',
          tenancy_rental: '',
          tenancy_deposit: '',
          beneficiary_id: '',
          beneficiary_name: '',
          beneficiary_idtype_name: '',
          beneficiary_idvalue: '',
          tenant_id: '',
          tenant_name: '',
          tenant_idtype_name: '',
          tenant_idvalue: '',
          property_name: '',
          unit_blocknumber: '',
          unit_unitnumber: '',
          unit_address: '',
          profile_id: '',
          profile_name: '',
          profile_roc: '',
          profile_address: '',
          arc_status: '',
          room_name: '',
          agreement_url: ''
        }
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

  Vue.component('form-tenancy', {
    template: '#form-tenancy-template',
    props: ['data'],
    data() {
      return {
        steps: this.getStepData(),
        form1: this.getForm1Data(),
        form2: this.getForm2Data(),
        form3: this.getForm3Data(),
        form4: this.getForm4Data(),
        formradio: this.getFormRadioOption(),
        tenant_options: [],
        idtype_options: [],
        property_options: [],
        unit_options: [],
        gender_options: [],
        nationality_options: [],
        race_options: [],
        beneficiary_options: [],
        durationmonth_options: [
          {
            id: 1,
            monthnum: 1
          },
          {
            id: 2,
            monthnum: 2
          },
          {
            id: 3,
            monthnum: 3
          },
          {
            id: 6,
            monthnum: 6
          },
          {
            id: 12,
            monthnum: 12
          },
          {
            id: 3,
            monthnum: 24
          },
          {
            id: 36,
            monthnum: 36
          },
        ]
      }
    },
    mounted() {
      this.fetchTenantsOptions()
      this.fetchIdtypeSearchOptions()
    },
    methods: {
      onSubmit() {
        axios.post('/api/tenancy/store-update', this.form).then((response) => {
          $('.modal').modal('hide');
          $('.modal').on('shown.bs.modal', function (e) {
            $(".modal").modal('hide');
          })
          for (var key in this.form) {
            this.form[key] = null;
          }
          this.$emit('updatetable')
        });
      },
      customLabelTenants(option) {
        return `(${option.name})  ${option.idtype_name}  ${option.id_value}  ${option.phone_number}  ${option.alt_phone_number ? option.alt_phone_number : ''}`
      },
      customLabelProperties(option) {
        return `(${option.name}) - ${option.propertytype_name} - ${option.address}  ${option.postcode ? option.postcode : ''}  ${option.city ? option.city : ''} ${option.state ? option.state : ''}`
      },
      customLabelUnits(option) {
        return `${option.block_number ? option.block_number + ' - ' : ''}  (${option.unit_number})  ${option.address ? ' - ' + option.address : ''}`
      },
      customLabelDurationMonth(option) {
        return `${option.monthnum} Month(s)`
      },
      customLabelBeneficiaries(option) {
        return `(${option.name})  ${option.idtype_name}  ${option.id_value}  ${option.phone_number}  ${option.alt_phone_number ? option.alt_phone_number : ''}`
      },
      fetchTenantsOptions() {
        axios.get('/api/tenants/all').then((response) => {
          this.tenant_options = response.data
        });
      },
      fetchIdtypeSearchOptions() {
        axios.get('/api/idtypes/all').then((response) => {
          this.idtype_options = response.data
        });
      },
      fetchPropertytypeSearchOptions() {
        axios.get('/api/propertytypes/all').then((response) => {
          this.propertytype_options = response.data
        });
      },
      fetchPropertySearchOptions() {
        axios.get('/api/properties/all').then((response) => {
          this.property_options = response.data
        });
      },
      fetchUnitSearchOptions() {
        var property_id = this.form2.property_id.id
        axios.get('/api/units/property/' + property_id).then((response) => {
          this.unit_options = response.data
        });
      },
      fetchGendersOptions() {
        axios.get('/api/genders/all').then((response) => {
          this.gender_options = response.data
        });
      },
      fetchNationalitiesOptions() {
        axios.get('/api/countries/all').then((response) => {
          this.nationality_options = response.data
        });
      },
      fetchRacesOptions() {
        axios.get('/api/races/all').then((response) => {
          this.race_options = response.data
        });
      },
      fetchBeneficiariesOptions() {
        axios.get('/api/beneficiaries/all').then((response) => {
          this.beneficiary_options = response.data
        });
      },
      setObjectNull(objs) {
        this.form1 = this.getForm1Data()
      },
      getForm1Data() {
        return {
          tenant_id: '',
          name: '',
          idtype_id: '',
          id_value: '',
          phone_number: '',
          alt_phone_number: '',
          email: ''
        }
      },
      getForm2Data() {
        return {
          property_id: '',
          name: '',
          ptd_code: '',
          address: '',
          postcode: '',
          city: '',
          state: '',
          attn_name: '',
          attn_phone_number: '',
          type_id: '',
          unit: this.getUnitData()
        }
      },
      getForm3Data() {
        return {
          room_name: '',
          tenancy_date: moment().format('YYYY-MM-DD'),
          datefrom: moment().format('YYYY-MM-DD'),
          dateto: '',
          duration_month: '',
          rental: '',
          deposit: 0,
          arc: false,
          tenant_register_user: false
        }
      },
      getUnitData() {
        return {
          unit_id: '',
          block_number: '',
          unit_number: '',
          address: '',
          label: '',
          remarks: '',
          squarefeet: '',
          purchase_price: '',
          bedbath_room: '',
          purchased_at: moment().format('YYYY-MM-DD')
        }
      },
      getForm4Data() {
        return {
          beneficiary_id: '',
          name: '',
          idtype_id: '',
          id_value: '',
          phone_number: '',
          alt_phone_number: '',
          email: '',
          address: '',
          city: '',
          state: '',
          postcode: '',
          occupation: '',
          invest_property_num: '',
          remarks: '',
          gender_id: '',
          nationality_id: '',
          race_id: ''
        }
      },
      getFormRadioOption() {
        return {
          step1: 1,
          step2: 1,
          step2_1: 1,
          step3: 1,
          step4: 1
        }
      },
      getStepData() {
        return {
          step1: true,
          step2: false,
          step3: false,
          step4: false,
          preview: false,
        }
      },
      resetObject(step) {
        switch (step) {
          case 1:
            this.form1 = this.getForm1Data()
            break;
          case 2:
            this.form2 = this.getForm2Data()
            if (this.formradio.step2 == 2) {
              this.formradio.step2_1 = 2
            } else {
              this.formradio.step2_1 = 1
            }
            this.form2.unit = this.getUnitData()
            break;
          case 2.1:
            this.form2.unit = this.getUnitData()
            break;
          case 4:
            this.form4 = this.getForm4Data()
            break;
        }
      },
      onPropertyIdOptionChanged() {
        this.form2.unit = this.getUnitData()
        this.fetchUnitSearchOptions()
      },
      onDurationMonthChanged() {
        this.form3.dateto = this.getMonthYearCal(this.form3.duration_month.monthnum)
      },
      nextStep() {
        if (this.steps.step1) {
          this.fetchPropertySearchOptions()
          this.fetchPropertytypeSearchOptions()
          this.steps.step1 = false
          this.steps.step2 = true
        } else if (this.steps.step2) {
          if (this.form2.property_id) {
            this.fetchUnitSearchOptions()
          }
          this.fetchPropertytypeSearchOptions()
          this.steps.step2 = false
          this.steps.step3 = true
        } else if (this.steps.step3) {
          this.fetchGendersOptions()
          this.fetchNationalitiesOptions()
          this.fetchRacesOptions()
          this.fetchBeneficiariesOptions()
          this.steps.step3 = false
          this.steps.step4 = true
        } else if (this.steps.step4) {
          this.steps.step4 = false
          this.steps.preview = true
        }
      },
      prevStep() {
        if (this.steps.step2) {
          this.steps.step2 = false
          this.steps.step1 = true
        } else if (this.steps.step3) {
          this.steps.step3 = false
          this.steps.step2 = true
        } else if (this.steps.step4) {
          this.steps.step4 = false
          this.steps.step3 = true
        } else if (this.steps.preview) {
          this.steps.preview = false
          this.steps.step4 = true
        }
      },
      submitForm() {
        axios.post('/api/tenancy/store-update', {
          tenantForm: this.form1,
          propertyForm: this.form2,
          tenancyForm: this.form3,
          beneficiaryForm: this.form4
        }).then((response) => {
          this.resetAllFormFields()
          this.$emit('updatetable')
        })
      },
      getMonthYearCal(monthnum) {
        return moment(this.form3.datefrom).add(monthnum, 'months').subtract(1, 'days').format('YYYY-MM-DD')
      },
      onDateChanged(value) {
        this.form3[value] = moment(this.form3[value]).format('YYYY-MM-DD')
      },
      onDateFromChanged() {
        if (this.form3.duration_month) {
          this.onDurationMonthChanged()
        }
        this.onDateChanged('datefrom')
      },
      resetAllFormFields() {
        this.steps = this.getStepData()
        this.form1 = this.getForm1Data()
        this.form2 = this.getForm2Data()
        this.form3 = this.getForm3Data()
        this.form4 = this.getForm4Data()
        this.formradio = this.getFormRadioOption()
      }
    },
    watch: {
      'data'(val) {
        for (var key in this.form) {
          this.form[key] = this.data[key];
        }
      },
    },
    computed: {
      disableNext() {
        var disable = true
        if (this.steps.step1 == true) {
          if ((this.form1.tenant_id) || (this.form1.name && this.form1.idtype_id && this.form1.id_value && this.form1.phone_number)) {
            disable = false
          }
        }

        if (this.steps.step2 == true) {
          if (((this.form2.property_id) || (this.form2.name && this.form2.city && this.form2.state && this.form2.type_id)) && (this.form2.unit.unit_id || (this.form2.unit.unit_number))) {
            disable = false
          }
        }

        if (this.steps.step3 == true) {
          if (this.form3.tenancy_date && this.form3.datefrom && this.form3.duration_month && this.form3.dateto && this.form3.rental) {
            disable = false
          }
        }

        if (this.steps.step4 == true) {
          if (this.form4.beneficiary_id || (this.form4.name && this.form4.idtype_id && this.form4.id_value && this.form4.city)) {
            disable = false
          }
        }

        return disable
      }
    }
  });

  new Vue({
    el: '#indexTenancyController',
  });
}