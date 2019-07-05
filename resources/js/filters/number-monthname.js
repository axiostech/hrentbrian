Vue.filter('monthname', function (value) {
  if(value) {
    return moment().month(value - 1).format('MMMM')
  }
});