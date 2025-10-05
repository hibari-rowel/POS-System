import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import timezone from "dayjs/plugin/timezone";

dayjs.extend(utc);
dayjs.extend(timezone);

export default class DateFormatter {
    static toReadable(dateString: string): string {
        return dayjs(dateString)
            .tz("Asia/Manila")
            .format("MMMM D, YYYY h:mm A");
    }

    static toShort(dateString: string): string {
        return dayjs(dateString.replace(" ", "T"))
            .tz("Asia/Manila")
            .format("MMM D, YYYY");
    }
}