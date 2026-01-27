import moment from "moment/moment";
import 'moment-timezone';

export default {
    /**
     * Parsea una fecha en formato YYYY-MM-DD como hora LOCAL (no UTC).
     * Evita el problema de que new Date('2026-01-27') se interprete como UTC
     * y retroceda un día en zonas horarias negativas como Perú (GMT-5).
     * @param {string} dateStr - Fecha en formato YYYY-MM-DD
     * @returns {Date} - Objeto Date en hora local
     */
    parseLocalDate(dateStr) {
        if (!dateStr) return new Date();
        const [year, month, day] = dateStr.split('-').map(Number);
        return new Date(year, month - 1, day); // month es 0-indexed
    },

    /**
     * Obtiene la fecha de hoy en formato YYYY-MM-DD usando hora LOCAL.
     * Evita el problema de toISOString() que puede dar la fecha incorrecta
     * después de las 7pm en Perú (cuando UTC ya es el día siguiente).
     * @returns {string} - Fecha de hoy en formato YYYY-MM-DD
     */
    todayString() {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    },

    /**
     * Agrega días a una fecha string y retorna el resultado en formato YYYY-MM-DD.
     * @param {string} dateStr - Fecha en formato YYYY-MM-DD
     * @param {number} days - Número de días a agregar (puede ser negativo)
     * @returns {string} - Nueva fecha en formato YYYY-MM-DD
     */
    addDaysToDate(dateStr, days) {
        const date = this.parseLocalDate(dateStr);
        date.setDate(date.getDate() + days);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    },

    dateForHumans(date) {
        return moment(date, "YYYY-MM-DD").locale("es").format("dddd DD MMMM YYYY");
    },

    dateDiff(date) {
        return moment(date).tz("America/Lima").locale("es").fromNow();
    },

    /**
     * Convierte un Date object a formato YYYY-MM-DD.
     * @param {Date} date - Objeto Date
     * @returns {string} - Fecha en formato YYYY-MM-DD
     */
    dateForLaravel(date) {
        const d = date instanceof Date ? date : this.parseLocalDate(date);
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
