import moment from "moment/moment";
import 'moment-timezone';

export default {
    dateForHumans(date) {
        return moment(date, "YYYY-MM-DD").locale("es").format("dddd DD MMMM YYYY");
    },

    dateDiff(date) {
        return moment(date).tz("America/Lima").locale("es").fromNow();
    },

    dateForLaravel(date) {
        const d = new Date(date);
        const year = d.getFullYear();
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    },

    
    dateForApp(date) {
        return moment(date, "YYYY-MM-DD").locale("es").format("dddd DD/MM");
    },

    hourForHumans(hour) {
        return moment(hour, "HH:mm").tz("America/Lima").format("h:mm A");
    },

    hourForPC(hour) {
        return moment(hour, "HH:mm").tz("America/Lima").format("HH:mm");
    },

    moment,
};
