Vue.filter('boolean', function (value) {
  if (value) {
    return 'Yes';
  } else {
    return 'No';
  }
});