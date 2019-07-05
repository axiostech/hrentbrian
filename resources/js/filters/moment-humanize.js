Vue.filter('datehumanize', function (dateto) {


  var duration = moment(dateto).diff(moment(), 'days')
  // var msg = moment.duration(duration, 'days').humanize()
  // duration().humanize()

  return duration
});