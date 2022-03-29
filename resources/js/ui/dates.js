import moment from "moment/moment";

export default {
    dateForHumans(date) {
        return moment(date).locale("es").format("dddd DD MMMM YYYY");
    },

    dateDiff(date) {
        return moment(date).locale("es").fromNow();
    },

    dateForLaravel(date) {
        return moment(date).format("YYYY-MM-DD");
    },

    
    dateForApp(date) {
        return moment(date).locale("es").format("dddd DD/MM");
    },

    hourForHumans(hour) {
        return moment(hour, "HH:mm").format("h:mm A");
    },

    hourForPC(hour) {
        return moment(hour, "HH:mm").format("HH:mm");
    },

    moment,
};
