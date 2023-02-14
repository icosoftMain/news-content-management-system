import { Login, Users, UI, AdminTableSearchEngine, Pagination, Tables, SpeakerSchedule, Messages, DashChart, SidePage } from './modules/load';
/**
*  @function Main
*  @param modules
*  @todo access all loaded script modules
*/

function Main() {
  new App.Main_Activity();
}

;
var App;

(function (App) {
  var Main_Activity =
  /** @class */
  function () {
    function Main_Activity() {
      this.engage();
    }

    Main_Activity.prototype.engage = function () {
      this.loginUser();
      this.setDashChart();
      this.deleteUser();
      this.selectPageSubCategory();
      this.initSearch();
      this.setPagination();
      this.setTables();
      this.setSpeakerSchedule();
      this.initMessage();
      this.setSidePage();
    };

    Main_Activity.prototype.setSidePage = function () {
      this.$class = new SidePage();
      this.$class.create();
    };
    
    Main_Activity.prototype.setDashChart = function () {
      this.$class = new DashChart();
      this.$class.getStat();
    };

    Main_Activity.prototype.setTables = function () {
      this.$class = new Tables();
      this.$class.setActiveTable();
    };

    Main_Activity.prototype.initMessage = function () {
      this.$class = new Messages();
      this.$class.getReadAndUnreadMessages();
      this.$class.updateMessageStatus();
    };

    Main_Activity.prototype.setSpeakerSchedule = function() {
      this.$class = new SpeakerSchedule();
      this.$class.setSpeakerSchedule();
    }

    Main_Activity.prototype.initSearch = function () {
      this.$class = new AdminTableSearchEngine();
      this.$class.init();
    };

    Main_Activity.prototype.setPagination = function () {
      this.$class = new Pagination();
      this.$class.init();
    };

    Main_Activity.prototype.selectPageSubCategory = function () {
      this.$class = new UI();
      this.$class.componentDidMount();
    };

    Main_Activity.prototype.loginUser = function () {
      this.$class = new Login();
      this.$class.logUser();
    };

    Main_Activity.prototype.deleteUser = function () {
      this.$class = new Users();
      this.$class.delete();
    };

    return Main_Activity;
  }();

  App.Main_Activity = Main_Activity;
})(App || (App = {}));

export default Main;