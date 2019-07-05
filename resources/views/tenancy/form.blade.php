<template id="form-tenancy-template">
  <div class="modal" id="tenancy_modal">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <div class="modal-title">
                    New Tenancy
                  </div>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>

              {{-- step1 tenant --}}

              <div class="modal-body">
                <div v-if="steps.step1">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="text-center">
                            <h3>
                                <span class="badge badge-light">
                                    Step 1 - Tenant
                                </span>
                            </h3>
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" value="1" v-model="formradio.step1" @change="resetObject(1)">
                            <label class="form-check-label">Existing</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" value="2" v-model="formradio.step1" @change="resetObject(1)">
                            <label class="form-check-label">Create New</label>
                        </div>
                    </div>

                    <div style="padding-top: 20px;">
                        <div class="form-group col-md-12 col-sm-12 col-xs-12" v-if="formradio.step1 == 1">
                            <label class="control-label">
                                Existing Tenants
                            </label>
                            <label for="required" class="control-label" style="color:red;">*</label>

                            <multiselect v-model="form1.tenant_id" placeholder="Select Tenants" label="name" track-by="id" :options="tenant_options" :internal-search="true" :custom-label="customLabelTenants">
                                <template slot="option" slot-scope="props">
                                    <span class="row col-md-12">
                                        @{{props.option.name}}
                                    </span>
                                    <span class="row col-md-12">
                                        @{{props.option.idtype_name}}: &nbsp;
                                        @{{props.option.id_value}}
                                    </span>
                                    <span class="row col-md-12">
                                        @{{props.option.phone_number}} <span v-if="props.option.alt_phone_number">&nbsp;OR&nbsp;</span>
                                        @{{props.option.alt_phone_number}}
                                    </span>
                                </template>
                            </multiselect>
                        </div>
                        <div v-if="formradio.step1 == 2">
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <label class="control-label">
                                    Name
                                </label>
                                <label for="required" class="control-label" style="color:red;">*</label>
                                <input type="text" name="name" class="form-control" v-model="form1.name">
                            </div>
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <label class="control-label">
                                    ID Type
                                </label>
                                <label for="required" class="control-label" style="color:red;">*</label>
                                <select2 v-model="form1.idtype_id">
                                    <option value="">All</option>
                                    <option v-for="idtype in idtype_options" :value="idtype.id">
                                        @{{idtype.name}}
                                    </option>
                                </select2>
                            </div>
                            <div class="form-group col-md-12 col-sm-12 col-xs-12" v-if="form1.idtype_id">
                                <label class="control-label">
                                    @{{idtype_options[form1.idtype_id - 1].name}} Number
                                </label>
                                <label for="required" class="control-label" style="color:red;">*</label>
                                <input type="text" name="id_value" class="form-control" v-model="form1.id_value">
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-row">
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label">
                                            Phone Number
                                        </label>
                                        <label for="required" class="control-label" style="color:red;">*</label>
                                        <input type="text" name="phone_number" class="form-control" v-model="form1.phone_number">
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label">
                                            Alt Phone Number
                                        </label>
                                        <input type="text" name="alt_phone_number" class="form-control" v-model="form1.alt_phone_number">
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label">
                                            Email
                                        </label>
                                        <label for="required" class="control-label" style="color:red;">*</label>
                                        <input type="text" name="email" class="form-control" v-model="form1.email">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- step2  --}}

                <div v-if="steps.step2">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="text-center">
                            <h3>
                                <span class="badge badge-light">
                                    Step 2 - Property & Unit
                                </span>
                            </h3>
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" value="1" v-model="formradio.step2" @change="resetObject(2)">
                            <label class="form-check-label">Existing Property</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" value="2" v-model="formradio.step2" @change="resetObject(2)">
                            <label class="form-check-label">Create New Property</label>
                        </div>
                    </div>

                    <div style="padding-top: 20px;">
                        <div class="form-group col-md-12 col-sm-12 col-xs-12" v-if="formradio.step2 == 1">
                            <label class="control-label">
                                Existing Properties
                            </label>
                            <label for="required" class="control-label" style="color:red;">*</label>

                            <multiselect v-model="form2.property_id" placeholder="Select Property" label="name" track-by="id" :options="property_options" :internal-search="true" :custom-label="customLabelProperties" @input="onPropertyIdOptionChanged">
                                <template slot="option" slot-scope="props">
                                    <span class="row col-md-12">
                                        @{{props.option.name}}
                                    </span>
                                    <span class="row col-md-12" v-if="props.option.propertytype_name">
                                        (@{{props.option.propertytype_name}})
                                    </span>
                                    <span class="row col-md-12">
                                        @{{props.option.address}}
                                    </span>
                                    <span class="row col-md-12">
                                        @{{props.option.postcode}} &nbsp; @{{props.option.city}} &nbsp; @{{props.option.state}}
                                    </span>
                                </template>
                            </multiselect>
                        </div>
                        <div v-if="formradio.step2 == 2">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-row">
                                    <div class="form-group col-md-9 col-sm-6 col-xs-12">
                                        <label class="control-label">
                                            Name
                                        </label>
                                        <label for="required" class="control-label" style="color:red;">*</label>
                                        <input type="text" name="name" class="form-control" v-model="form2.name">
                                    </div>
                                    <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label">
                                            PTD Code
                                        </label>
                                        <input type="text" name="ptd_code" class="form-control" v-model="form2.ptd_code">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <label class="control-label">
                                    Address
                                </label>
                                <textarea name="address" class="form-control" rows="3" v-model="form2.address"></textarea>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-row">
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label">
                                            Postcode
                                        </label>
                                        <input type="text" name="postcode" class="form-control" v-model="form2.postcode">
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label">
                                            City
                                        </label>
                                        <label for="required" class="control-label" style="color:red;">*</label>
                                        <input type="text" name="city" class="form-control" v-model="form2.city">
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label">
                                            State
                                        </label>
                                        <label for="required" class="control-label" style="color:red;">*</label>
                                        <input type="text" name="state" class="form-control" v-model="form2.state">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                        <label class="control-label">
                                            Attn Name
                                        </label>
                                        <input type="text" name="attn_name" class="form-control" v-model="form2.attn_name">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                        <label class="control-label">
                                            Attn Phone Number
                                        </label>
                                        <input type="text" name="attn_phone_number" class="form-control" v-model="form2.attn_phone_number">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <label class="control-label">
                                    Property Type
                                </label>
                                <label for="required" class="control-label" style="color:red;">*</label>
                                <select2 v-model="form2.type_id">
                                    <option value="">All</option>
                                    <option v-for="type in propertytype_options" :value="type.value">
                                    @{{type.label}}
                                </select2>
                            </div>
                        </div>
                    </div>
                    <hr v-if="formradio.step2 == 2 || form2.property_id">
                    <div class="col-md-12 col-sm-12 col-xs-12" v-if="formradio.step2 == 2 || form2.property_id" style="padding-top: 20px;">
                        <div class="form-check form-check-inline" v-if="formradio.step2 != 2">
                            <input class="form-check-input" type="radio" value="1" v-model="formradio.step2_1" @change="resetObject(2.1)">
                            <label class="form-check-label">Existing Unit</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" value="2" v-model="formradio.step2_1" @change="resetObject(2.1)">
                            <label class="form-check-label">Create New Unit</label>
                        </div>
                    </div>
                    <div style="padding-top: 20px;" v-if="formradio.step2 == 2 || form2.property_id">
                        <div class="form-group col-md-12 col-sm-12 col-xs-12" v-if="formradio.step2_1 == 1">
                            <label class="control-label">
                                Existing Units
                            </label>
                            <label for="required" class="control-label" style="color:red;">*</label>

                            <multiselect v-model="form2.unit.unit_id" placeholder="Select Unit" label="name" track-by="id" :options="unit_options" :internal-search="true" :custom-label="customLabelUnits">
                                <template slot="option" slot-scope="props">
                                    <span class="row col-md-12">
                                        @{{props.option.block_number}}
                                        <span v-if="props.option.block_number"> - </span>
                                        @{{props.option.unit_number}}
                                    </span>
                                    <span class="row col-md-12">
                                        @{{props.option.address}}
                                    </span>
                                </template>
                            </multiselect>
                        </div>
                        <div v-if="formradio.step2_1 == 2">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                        <label class="control-label">
                                            Block Number
                                        </label>
                                        <input type="text" name="block_number" class="form-control" v-model="form2.unit.block_number">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                        <label class="control-label">
                                            Unit Number
                                        </label>
                                        <label for="required" class="control-label" style="color:red;">*</label>
                                        <input type="text" name="unit_number" class="form-control" v-model="form2.unit.unit_number">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-row">
                                    <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label">
                                            Square Feet
                                        </label>
                                        <input type="text" name="squarefeet" class="form-control text-right" v-model="form2.unit.squarefeet">
                                    </div>
                                    <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label">
                                            Purchase Price (RM)
                                        </label>
                                        <input type="text" name="purchase_price" class="form-control text-right" v-model="form2.unit.purchase_price">
                                    </div>
                                    <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label">
                                            Bed & Bath Room
                                        </label>
                                        <input type="text" name="bedbath_room" class="form-control" v-model="form2.unit.bedbath_room" placeholder="Ex. 3 Bed 2 Bath">
                                    </div>
                                    <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label">
                                            Purchase Date
                                        </label>
                                        <i class="far fa-calendar-alt"></i>
                                        <datepicker v-model="form2.unit.purchased_at" format="yyyy-MM-dd" :bootstrap-styling="true" @input="onDateFromChanged"></datepicker>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                        <label class="control-label">
                                            Road Name
                                        </label>
                                        <textarea name="address" class="form-control" rows="2" v-model="form2.unit.address"></textarea>
                                    </div>
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                        <label class="control-label">
                                            Remarks
                                        </label>
                                        <textarea name="remarks" class="form-control" rows="2" v-model="form2.unit.remarks"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- step 3 tenancy details --}}

                <div v-if="steps.step3">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="text-center">
                            <h3>
                                <span class="badge badge-light">
                                    Step 3 - Tenancy Details
                                </span>
                            </h3>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label">
                            Room Name
                        </label>
                        <input type="text" name="room_name" class="form-control" v-model="form3.room_name">
                    </div>
                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label">
                            Date of Agreement
                        </label>
                        <i class="far fa-calendar-alt"></i>
                        <label for="required" class="control-label" style="color:red;">*</label>
                        <datepicker v-model="form3.tenancy_date" format="yyyy-MM-dd" :bootstrap-styling="true" @input="onDateChanged('tenancy_date')"></datepicker>
                    </div>
                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label">
                            Date of Commencement
                        </label>
                        <i class="far fa-calendar-alt"></i>
                        <label for="required" class="control-label" style="color:red;">*</label>
                        <datepicker v-model="form3.datefrom" format="yyyy-MM-dd" :bootstrap-styling="true" @input="onDateFromChanged"></datepicker>
                    </div>
                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label">
                            Duration (Months)
                        </label>
                        <label for="required" class="control-label" style="color:red;">*</label>
                        <multiselect v-model="form3.duration_month" placeholder="How many months? Based on Date of Commencement" label="monthnum" track-by="id" :options="durationmonth_options" :internal-search="true" :custom-label="customLabelDurationMonth" @input="onDurationMonthChanged">
                            <template slot="option" slot-scope="props">
                                <span class="row col-md-12">
                                    @{{props.option.monthnum}} Month(s)
                                </span>
                                <span class="row col-md-12">
                                    @{{getMonthYearCal(props.option.monthnum)}}
                                </span>
                            </template>
                        </multiselect>
                    </div>
                    <div class="form-group col-md-12 col-sm-12 col-xs-12" v-if="form3.duration_month">
                        <label class="control-label">
                            Date of Termination
                        </label>
                        <i class="far fa-calendar-alt"></i>
                        <label for="required" class="control-label" style="color:red;">*</label>
                        <datepicker v-model="form3.dateto" format="yyyy-MM-dd" :bootstrap-styling="true" @input="onDateChanged('dateto')"></datepicker>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label class="control-label">
                                    Monthly Rental (RM)
                                </label>
                                <label for="required" class="control-label" style="color:red;">*</label>
                                <input type="text" name="rental" class="form-control text-right" v-model="form3.rental">
                            </div>
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label class="control-label">
                                    Deposit (RM)
                                </label>
                                <input type="text" name="rental" class="form-control text-right" v-model="form3.deposit">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" v-model="form3.arc" value="1">
                            <label class="form-check-label">
                                Auto Rental Collection (ARC)
                            </label>
                            <p>
                                <small>
                                    *By checking this checkbox, you are agreed with our terms and conditions for ARC functionality. ARC can only be triggered by sending invoice to the binded tenant.
                                    (Note: Tenant will have the final rights to approve the ARC, after user approval and had successfully binded to Credit Card OR Online Banking Account, it requires 3 working days to get approved by bank.)
                                </small>
                            </p>
                        </div>
{{--
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" v-model="form3.tenant_register_user" value="1">
                            <label class="form-check-label">
                                Create Tenant Login?
                            </label>
                            <p>
                                <small>
                                    *By checking this checkbox, tenant is able to login to the system as a user. (Make sure tenant has filled email address OR mobile number)
                                </small>
                            </p>
                        </div> --}}
                    </div>
                </div>

                {{-- step4 beneficiaries --}}

                <div v-if="steps.step4">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="text-center">
                            <h3>
                                <span class="badge badge-light">
                                    Step 4 - Beneficiary
                                </span>
                            </h3>
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" value="1" v-model="formradio.step4" @change="resetObject(4)">
                            <label class="form-check-label">Existing</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" value="2" v-model="formradio.step4" @change="resetObject(4)">
                            <label class="form-check-label">Create New</label>
                        </div>
                    </div>

                    <div style="padding-top: 20px;">
                        <div class="form-group col-md-12 col-sm-12 col-xs-12" v-if="formradio.step4 == 1">
                            <label class="control-label">
                                Existing Beneficiaries
                            </label>
                            <label for="required" class="control-label" style="color:red;">*</label>

                            <multiselect v-model="form4.beneficiary_id" placeholder="Select Beneficiary" label="name" track-by="id" :options="beneficiary_options" :internal-search="true" :custom-label="customLabelBeneficiaries">
                                <template slot="option" slot-scope="props">
                                    <span class="row col-md-12">
                                        @{{props.option.name}}
                                    </span>
                                    <span class="row col-md-12">
                                        @{{props.option.idtype_name}}: &nbsp;
                                        @{{props.option.id_value}}
                                    </span>
                                    <span class="row col-md-12">
                                        @{{props.option.phone_number}} <span v-if="props.option.alt_phone_number">&nbsp;OR&nbsp;</span>
                                        @{{props.option.alt_phone_number}}
                                    </span>
                                </template>
                            </multiselect>
                        </div>
                        <div v-if="formradio.step4 == 2">
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <label class="control-label">
                                    Name
                                </label>
                                <label for="required" class="control-label" style="color:red;">*</label>
                                <input type="text" name="name" class="form-control" v-model="form4.name">
                            </div>
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <label class="control-label">
                                    ID Type
                                </label>
                                <label for="required" class="control-label" style="color:red;">*</label>
                                <select2 v-model="form4.idtype_id">
                                    <option value="">All</option>
                                    <option v-for="idtype in idtype_options" :value="idtype.id">
                                        @{{idtype.name}}
                                    </option>
                                </select2>
                            </div>
                            <div class="form-group col-md-12 col-sm-12 col-xs-12" v-if="form4.idtype_id">
                                <label class="control-label">
                                    @{{idtype_options[form4.idtype_id - 1].name}} Number
                                </label>
                                <label for="required" class="control-label" style="color:red;">*</label>
                                <input type="text" name="id_value" class="form-control" v-model="form4.id_value">
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-row">
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label">
                                            Gender
                                        </label>
                                        <select2 v-model="form4.gender_id">
                                            <option value="">All</option>
                                            <option v-for="gender in gender_options" :value="gender.id">
                                                @{{gender.name}}
                                            </option>
                                        </select2>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label">
                                            Nationality
                                        </label>
                                        <select2 v-model="form4.nationality_id">
                                            <option value="">All</option>
                                            <option v-for="nationality in nationality_options" :value="nationality.id">
                                                @{{nationality.name}}
                                            </option>
                                        </select2>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label">
                                            Race
                                        </label>
                                        <select2 v-model="form4.race_id">
                                            <option value="">All</option>
                                            <option v-for="race in race_options" :value="race.id">
                                                @{{race.name}}
                                            </option>
                                        </select2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-row">
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label">
                                            Phone Number
                                        </label>
                                        <label for="required" class="control-label" style="color:red;">*</label>
                                        <input type="text" name="phone_number" class="form-control" v-model="form4.phone_number">
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label">
                                            Alt Phone Number
                                        </label>
                                        <input type="text" name="alt_phone_number" class="form-control" v-model="form4.alt_phone_number">
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label">
                                            Email
                                        </label>
                                        <input type="text" name="email" class="form-control" v-model="form4.email">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <label class="control-label">
                                    Address
                                </label>
                                <textarea name="address" class="form-control" v-model="form4.address" rows="2"></textarea>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-row">
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label">
                                            Postcode
                                        </label>
                                        <input type="text" name="postcode" class="form-control" v-model="form4.postcode">
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label">
                                            City
                                        </label>
                                        <label for="required" class="control-label" style="color:red;">*</label>
                                        <input type="text" name="city" class="form-control" v-model="form4.city">
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label">
                                            State
                                        </label>
                                        <input type="text" name="state" class="form-control" v-model="form4.state">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                        <label class="control-label">
                                            Occupation
                                        </label>
                                        <input type="text" name="occupation" class="form-control" v-model="form4.occupation">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                        <label class="control-label">
                                            Number of Invested Property
                                        </label>
                                        <input type="text" name="invest_property_num" class="form-control" v-model="form4.invest_property_num" placeholder="Numbers Only">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <label class="control-label">
                                    Remarks
                                </label>
                                <textarea name="remarks" class="form-control" v-model="form4.remarks" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
{{--
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" v-model="form4.is_joint">
                            <label class="form-check-label">
                                Joint Beneficiary ?
                            </label>
                        </div>
                    </div> --}}
                </div>

                {{-- preview --}}

                <div v-if="steps.preview">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="text-center">
                            <h3>
                                <span class="badge badge-light">
                                    Draft
                                </span>
                            </h3>
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <table class="table table-bordered">
                            <tr>
                                <th scope="row">
                                    Date of Agreement
                                </th>
                                <td>
                                    @{{form3.tenancy_date}}
                                </td>
                            </tr>
                            <tr v-if="form4.beneficiary_id || form4.name">
                                <th scope="row">
                                    Landlord
                                </th>
                                <td>
                                    <span v-if="form4.beneficiary_id">
                                        @{{form4.beneficiary_id.name}}
                                        <br>
                                        @{{form4.beneficiary_id.idtype_name}}&nbsp;
                                        @{{form4.beneficiary_id.id_value}}
                                    </span>
                                    <span v-else>
                                        @{{form4.name}}
                                        <br>
                                        @{{form4.idtype_id}}&nbsp;
                                        @{{form4.id_value}}
                                    </span>
                                </td>
                            </tr>
                            <tr v-if="form1.tenant_id || form1.name">
                                <th scope="row">
                                    Tenant
                                </th>
                                <td>
                                    <span v-if="form1.tenant_id">
                                        @{{form1.tenant_id.name}}
                                        <br>
                                        @{{form1.tenant_id.idtype_name}}&nbsp;
                                        @{{form1.tenant_id.id_value}}
                                    </span>
                                    <span v-else>
                                        @{{form1.name}}
                                        <br>
                                        @{{form1.idtype_id}}&nbsp;
                                        @{{form1.id_value}}
                                    </span>
                                </td>
                            </tr>
                            <tr v-if="form4.beneficiary_id || form4.name">
                                <th scope="row">
                                    Owner
                                </th>
                                <td>
                                    <span v-if="form4.beneficiary_id">
                                        @{{form4.beneficiary_id.name}}
                                        <br>
                                        @{{form4.beneficiary_id.idtype_name}}&nbsp;
                                        @{{form4.beneficiary_id.id_value}}
                                    </span>
                                    <span v-else>
                                        @{{form4.name}}
                                        <br>
                                        @{{form4.idtype_id}}&nbsp;
                                        @{{form4.id_value}}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    Demised Premises
                                </th>
                                <td>

                                    <span v-if="form2.property_id">
                                        @{{form2.property_id.name}}
                                    </span>
                                    <span v-else>
                                        @{{form2.name}}
                                    </span>
                                    <br>
                                    <span v-if="form2.unit.unit_id">
                                        @{{form2.unit.unit_id.block_number}} @{{form2.unit.unit_id.unit_number}} @{{form2.unit.unit_id.address}}
                                    </span>
                                    <span v-else>
                                        @{{form2.unit.block_number}} @{{form2.unit.unit_number}} @{{form2.unit.address}}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    Duration of Tenancy
                                </th>
                                <td>
                                    @{{form3.duration_month.monthnum}} Month(s)
                                    <br>
                                    @{{form3.datefrom}} (Commencement)
                                    <br>
                                    @{{form3.dateto}} (Termination)
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

              </div>

              <div class="modal-footer">
                  <div class="btn-group">
                    <button type="button" class="btn btn-info text-white" style="border: black solid 1px;" @click.prevent="prevStep" v-if="steps.step2 || steps.step3 || steps.step4 || steps.preview">
                        Back
                        <i class="fas fa-backward"></i>
                    </button>
                    <button type="button" class="btn btn-success" style="border: black solid 1px;" @click.prevent="nextStep" v-if="steps.step4">
                        Skip for Now
                        <i class="fas fa-forward"></i>
                    </button>
                    <button type="button" class="btn btn-success" style="border: black solid 1px;" :disabled="disableNext" @click.prevent="nextStep" v-if="steps.step1 || steps.step2 || steps.step3 || steps.step4">
                        Next
                        <i class="fas fa-check"></i>
                    </button>
                    <button type="button" class="btn btn-success" style="border: black solid 1px;" @click.prevent="submitForm" v-if="steps.preview">
                        Submit
                        <i class="far fa-check-square"></i>
                    </button>
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                  </div>
              </div>
          </div>
      </div>
  </div>
</template>