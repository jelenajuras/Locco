$(function() {
    var url_basic = location.origin + '/home';
    if($('.dataArr').text()) {
        var data1 = JSON.parse( $('.dataArr').text());
  
        $('.calender_view').pignoseCalendar({
            multiple: false,
            scheduleOptions: {
                colors: {
                    event: '#1390EA',
                    birthday: '#EA9413',
                    GO: '#00cc00',
                    IZL: '#00cc00',
                    BOL: '#00cc00',
                    LP: '#ff0000',
                    task: '#e600e6',
                }
            },
            schedules: data1,
                select: function(date, schedules, context) { 
                    /**
                     * @params this Element
                     * @params event MouseEvent
                     * @params context PignoseCalendarContext
                     * @returns void
                     */
                    var $this = $(this); // This is clicked button Element.
                    if(date[0] != null && date[0] != 'undefined') {
                        if(date[0]['_i'] != 'undefined' && date[0]['_i'] != null) {
                            var day = date[0]['_i'].split('-')[2];
                            var month = date[0]['_i'].split('-')[1]; // (from 0 to 11)
                            var year = date[0]['_i'].split('-')[0];
                            var datum = year + '-' + month + '-' + day;
                            var url = url_basic + '?dan=' + datum;
                            $('section.calendar .all_events').load(url + ' section.calendar .all_events .show_event');
                        }
                    }
                }
        });
    }
   
});