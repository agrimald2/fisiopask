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

    hourForHumans(hour) {
        return moment(hour, "HH:mm").format("h:mm A");
    },

    moment,
};
