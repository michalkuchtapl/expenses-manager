export function parseDateString(dateString) {
    const dateObject = new Date(dateString);

    const date = dateObject.getDate();
    const month = dateObject.getMonth() + 1;
    const year = dateObject.getFullYear();

    return `${date}-${month}-${year}`;
}

export function isDateStringInPast(dateString) {
    const currentDate = new Date();
    const date = new Date(dateString);
    return date.getTime() < currentDate.getTime();
}
