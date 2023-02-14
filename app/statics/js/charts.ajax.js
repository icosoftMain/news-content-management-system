//ct-visits

  let app = window.ezapp;    
    let ajax = app.ajax; 
   // let url = "../resource/userdetailsjson";
    function initPageVisitsChartsAjax(url){
     app.addEvent(window,"load",function(){
       let form = app.$.FORM;             
       let xhreq = ajax.xhr();       
      ajax.$_get(url);
      ajax.$_response(function(){
           //data = JSON.parse(xhreq.responseText);
           data = app.data.parse(xhreq.responseText);
           //console.trace(data);
          // console.log(xhreq);
      });
  
     });
  }
     new Chartist.Line('#ct-visits', {
         labels: ['2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015'],
         series: [ 
             [5, 2, 7, 4, 5, 3, 5, 4] , [2, 5, 2, 6, 2, 5, 2, 4]
         ]
     }, {
         top: 0,
         low: 1,
         showPoint: true,
         fullWidth: true,
         plugins: [
    Chartist.plugins.tooltip()
  ],
         axisY: {
             labelInterpolationFnc: function (value) {
                 return (value / 1) + 'k';
             }
         },
         showArea: true
     });


