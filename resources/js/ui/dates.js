import moment from "moment/moment";
import 'moment-timezone';

export default {
    dateForHumans(date) {
        console.log(date);
        return moment(date).tz("America/Lima").locale("es").format("dddd DD MMMM YYYY");
    },

    dateDiff(date) {
        return moment(date).tz("America/Lima").locale("es").fromNow();
    },

    dateForLaravel(date) {
        return moment(date).tz("America/Lima").format("YYYY-MM-DD");
    },

    
    dateForApp(date) {
        return moment(date).tz("America/Lima").locale("es").format("dddd DD/MM");
    },

    hourForHumans(hour) {
        return moment(hour, "HH:mm").tz("America/Lima").format("h:mm A");
    },

    hourForPC(hour) {
        return moment(hour, "HH:mm").tz("America/Lima").format("HH:mm");
    },

    moment,
};
