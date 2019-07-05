require('./bootstrap');
require('./form');
require('./error');

window.Vue = require('vue');

// npm elements
window.select2 = require('select2');
window.moment = require('moment');
window.events = new Vue();
window.flash = function (message, level = 'info') {
  window.events.$emit('flash', {message, level});
};

require('bootstrap-datepicker');
window.accounting = require('accounting-js');
$.fn.datetimepicker = require('eonasdan-bootstrap-datetimepicker');



// vue reusable components
// modal for vue
import BootstrapModal from 'vue2-bootstrap-modal';
Vue.component('bootstrap-modal', BootstrapModal);

import BootstrapVue from 'bootstrap-vue';
Vue.use(BootstrapVue)

// date and datetimepicker
import Datepicker from 'vuejs-datepicker';
Vue.component('datepicker', Datepicker);

import Datetimepicker from './components/Datetimepicker.vue';
Vue.component('datetimepicker', Datetimepicker);

// flash vue
import Flash from './components/Flash.vue';
Vue.component('flash', Flash);

// This imports all the layout components such as <b-container>, <b-row>, <b-col>:
import { Layout } from 'bootstrap-vue/es/components'
Vue.use(Layout)

// This imports <b-modal> as well as the v-b-modal directive as a plugin:
import { Modal } from 'bootstrap-vue/es/components'
Vue.use(Modal)

// vue multiselect
import Multiselect from 'vue-multiselect';
Vue.component('multiselect', Multiselect);

// This imports <b-card> along with all the <b-card-*> sub-components as a plugin:
import { Card } from 'bootstrap-vue/es/components'
Vue.use(Card)

// vue pagination component
import Pagination from './components/Pagination.vue';
Vue.component('pagination', Pagination);

// vue js loading
import PulseLoader from 'vue-spinner/src/PulseLoader.vue';
Vue.component('pulse-loader', PulseLoader);

// select2 vue
import Select2 from './components/Select2.vue';
Vue.component('select2', Select2);

// vue modal
import VModal from 'vue-js-modal'
Vue.component('v-modal', VModal);

// vue select
import vSelect from 'vue-select';
Vue.component('v-select', vSelect);
// This imports directive v-b-scrollspy as a plugin:
/*
import { Scrollspy } from 'bootstrap-vue/es/directives'
Vue.use(Scrollspy) */

// object oriented form and errors prompt
require('./form');
require('./error');

// vue reusable filters
require('./filters/boolean-filter');
require('./filters/currency-filter');
require('./filters/moment-humanize');
require('./filters/number-monthname');

// vue directives
// require('./directives/select-select2');

// vue controllers
require('./controllers/indexTenancyController');
require('./controllers/indexPermissionController');
require('./controllers/indexProfileController');
require('./controllers/indexPropertyController');
require('./controllers/indexTenantController');
require('./controllers/indexBeneficiaryController');
require('./controllers/indexInsuranceController');
require('./controllers/indexRoleController');
require('./controllers/indexUnitController');
require('./controllers/indexUserController');
require('./controllers/accountUserController');
require('./controllers/indexCheckoutController');
require('./controllers/indexTenantUtilityrecordController');
require('./controllers/indexUtilityrecordController');
require('./controllers/indexCollectionController');
require('./controllers/indexInvoiceController');


$(".sidebar-dropdown > a").click(function () {
  $(".sidebar-submenu").slideUp(200);
  if (
    $(this)
      .parent()
      .hasClass("active")
  ) {
    $(".sidebar-dropdown").removeClass("active");
    $(this)
      .parent()
      .removeClass("active");
  } else {
    $(".sidebar-dropdown").removeClass("active");
    $(this)
      .next(".sidebar-submenu")
      .slideDown(200);
    $(this)
      .parent()
      .addClass("active");
  }
});

$("#close-sidebar").click(function () {
  $(".page-wrapper").removeClass("toggled");
});
$("#show-sidebar").click(function () {
  $(".page-wrapper").addClass("toggled");
});

$('#checkAll').change(function () {
  var all = this;
  $(this).closest('table').find('input[type="checkbox"]').prop('checked', all.checked);
});
/*
$('.select').select2({
  placeholder: 'Select..'
}); */

// select 2 defocus on device upon open
/*
$('select').on('select2:open', function (e) {
  $('.select2-search input').prop('focus', false);
}) */

