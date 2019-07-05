Vue.filter('currency', {
  // model -> view
  read: function (val) {
    if (val > 0) {
      return accounting.formatNumber(val, 2, ",", ".");
    } else {
      return 0;
    }
  },
  // view -> model
  write: function (val, oldVal) {
    return accounting.unformat(val, ".");
  }
})