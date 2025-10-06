$(document).ready(function () {

    $("#reserveBtn").on("click", function () {
        let selectedSlots = [];
        $(".cell.selected").each(function () {
            let day = $(this).data("day");
            let hour = $(this).data("hour");
            selectedSlots.push({day, hour});
        });
        if (selectedSlots.length > 0) {
            console.log("رزروهای انتخاب شده: ", selectedSlots);
        } else {
            alert("لطفاً یک یا چند زمان را انتخاب کنید.");
        }
    });
});

function generateGrid(columns, startTime, endTime, startTime5, endTime5, startTime6, endTime6, startTimeh, endTimeh, hasLaunchTime, launchTIme, offTimes, reservedTime, prereservedTime) {
    const gridContainer = document.getElementById('grid-container');
    let startUnit = timeToUnits(startTime);
    let endUnit = timeToUnits(endTime);
    let startUnit5 = timeToUnits(startTime5);
    let endUnit5 = timeToUnits(endTime5);
    let startUnit6 = timeToUnits(startTime6);
    let endUnit6 = timeToUnits(endTime6);
    let startUnith = timeToUnits(startTimeh);
    let endUnith = timeToUnits(endTimeh);
    let startLaunch = timeToUnits(launchTIme);

    let minStart = Math.min(startUnit, startUnit5, startUnit6, startUnith);
    if (minStart % 2 === 1) {
        minStart--;
    }
    let maxEnd = Math.max(endUnit, endUnit5, endUnit6, endUnith);
    if (maxEnd % 2 === 1) {
        maxEnd++;
    }
    const weekDays = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنجشنبه', 'جمعه'];
    let maxDiff = (maxEnd - minStart) / 2;
    maxDiff % 2 === 1 ? maxDiff + 1 : maxDiff


    rows = maxDiff + 1
    gridContainer.innerHTML = '';
    tmpstart = minStart;
    cellnumber = 1;
    let cc = 1;
    for (let i = 1; i <= (rows * 2); i++) {
        gridContainer.appendChild(createBox(i + 1, 1, i, ` ${i} `, 'box2'));
    }
    cc = 1;
    for (let i = 0; i < (columns * rows); i++) {
        const columnStart = (i % columns) + 1;
        const rowStart = Math.floor(i / columns) + 1;
        const rowEnd = rowStart + 1;

        let rstart = rowStart
        let rend = rowEnd

        if (i === 0) {
            text = "روز / ساعت";
        } else if (i < columns) {
            text = `${weekDays[i - 1]}`;
        } else if (i % columns === 0) {
            rstart = Math.floor(i / columns) + cc;
            rend = Math.floor(i / columns) + cc + 2;
            cc++;
            text = ` ${unitsToTime(tmpstart)} - ${unitsToTime(tmpstart + 2)}`;
            tmpstart += 2;
        } else {
            continue;
        }

        gridContainer.appendChild(createBox(rend, columnStart, rstart, text));
    }
    duration = 90
    tmpstart = minStart;

    for (let i = 1; i < columns; i++) {
        let j = 1
        const endOfOffForEnd = maxEnd - minStart + 2;
        const gridColumn = i + 1;
        let workRange = 0
        let startOfOffForEnd = 0;
        let startOfLaunchTime = startLaunch - minStart + 2;
        if (weekDays[i - 1] == 'جمعه') {//jome
            if (startUnit6 > minStart) {
                const endOfOffForStart = startUnit6 - minStart + 2;
                gridContainer.appendChild(createBox(endOfOffForStart, gridColumn, 2, "", "disabledBox"));
                j = startUnit6 - minStart + 1
            }
            if (hasLaunchTime && startLaunch >= startUnit6 && startLaunch < endUnit6) {
                gridContainer.appendChild(createBox(startLaunch - minStart + 3, gridColumn, startOfLaunchTime, "", "launchBox"));
            }
            if ((endUnit6) < maxEnd) {
                startOfOffForEnd = endUnit6 - minStart + 2;
                gridContainer.appendChild(createBox(endOfOffForEnd, gridColumn, startOfOffForEnd, "", "disabledBox"));
            }
            workRange = endUnit6 - startUnit6
        } else if (weekDays[i - 1] == 'پنجشنبه') {//panjshanbe
            if (startUnit5 > minStart) {
                const endOfOffForStart = startUnit5 - minStart + 2;
                gridContainer.appendChild(createBox(endOfOffForStart, gridColumn, 2, "", "disabledBox"));
                j = startUnit5 - minStart + 1
            }
            if (hasLaunchTime && startLaunch >= startUnit5 && startLaunch < endUnit5) {
                gridContainer.appendChild(createBox(startLaunch - minStart + 3, gridColumn, startOfLaunchTime, "", "launchBox"));
            }
            if ((endUnit5) < maxEnd) {
                startOfOffForEnd = endUnit5 - minStart + 2;
                gridContainer.appendChild(createBox(endOfOffForEnd, gridColumn, startOfOffForEnd, "", "disabledBox"));
            }
            workRange = endUnit5 - startUnit5
        } else {
            if (weekDays[i - 1] == 'دوشنبه') {
                if (startUnith > minStart) {
                    const endOfOffForStart = startUnith - minStart + 2;
                    gridContainer.appendChild(createBox(endOfOffForStart, gridColumn, 2, "", "disabledBox"));
                    j = startUnith - minStart + 1
                }
                if (hasLaunchTime && startLaunch >= startUnith && startLaunch < endUnith) {
                    gridContainer.appendChild(createBox(startLaunch - minStart + 3, gridColumn, startOfLaunchTime, "", "launchBox"));
                }
                startOfOffForEnd = endUnith - minStart + 2;
                if ((endUnith) < maxEnd) {
                    gridContainer.appendChild(createBox(endOfOffForEnd, gridColumn, startOfOffForEnd, "", "disabledBox"));
                }
                workRange = endUnith - startUnith
            } else {
                if (startUnit > minStart) {
                    const endOfOffForStart = startUnit - minStart + 2;
                    gridContainer.appendChild(createBox(endOfOffForStart, gridColumn, 2, "", "disabledBox"));
                    j = startUnit - minStart + 1
                }
                if (hasLaunchTime && startLaunch >= startUnit && startLaunch < endUnit) {
                    gridContainer.appendChild(createBox(startLaunch - minStart + 3, gridColumn, startOfLaunchTime, "", "launchBox"));
                }
                if ((endUnit) < maxEnd) {
                    startOfOffForEnd = endUnit - minStart + 2;
                    gridContainer.appendChild(createBox(endOfOffForEnd, gridColumn, startOfOffForEnd, "", "disabledBox"));
                }
                workRange = endUnit - startUnit
            }
        }
        console.log(weekDays[i - 1] + "->workRange:" + workRange + " start:" + (j) + " end:" + ((startOfOffForEnd)))
        for (; j < (startOfOffForEnd - (duration / 30));) {
            if (hasLaunchTime && j < startOfLaunchTime && (j + (duration / 30)) >= startOfLaunchTime) {
                j = startOfLaunchTime
            } else {
                gridContainer.appendChild(createBox(j + 1 + (duration / 30), gridColumn, j + 1, "کاشت ناخن" + "<br>" + ` ${unitsToTime(j + minStart - 1)} - ${unitsToTime(j + minStart - 1 + (duration / 30))}`, ["box", "cell"]));
                j += (duration / 30)
            }
        }
    }
    $(".cell").on("click", function () {
        if ($(this).hasClass("resereved")) {
            alert("این نوبت رزرو شده است.")
        } else if ($(this).hasClass("preresereved")) {
            alert("این نوبت در حالت رزرو موقت میباشد لطفا دقایقی دیگر دوباره تلاش کنید.")
        } else {
            if ($(this).hasClass("selected")) {
                $(this).removeClass("selected");
            } else {
                $(".cell").removeClass("selected");
                $(this).addClass("selected");
            }
        }

    });
}

function timeToUnits(timeString, roundToEven) {
    const [hours, minutes, seconds] = timeString.split(':').map(num => parseInt(num));
    const totalMinutes = hours * 60 + minutes;
    return (Math.floor(totalMinutes / 30) % 2 === 1 && roundToEven) ? (Math.floor(totalMinutes / 30) + 1) : Math.floor(totalMinutes / 30);
}

function unitsToTime(unit) {
    const totalMinutes = unit * 30;
    const hours = Math.floor(totalMinutes / 60);
    const minutes = totalMinutes % 60;
    return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
}

function createBox(end, gridColumn, gridRowStart, text, classnames = "box") {
    const box = document.createElement('div');
    if (Array.isArray(classnames)) {
        classnames.forEach(cls => box.classList.add(cls));
    } else {
        box.classList.add(classnames);
    }
    box.innerHTML = text;
    box.style.gridColumnStart = gridColumn;
    box.style.gridRowStart = gridRowStart;
    box.style.gridRowEnd = end;
    return box;
}

generateGrid(8, "09:00:00", "19:30:00", "08:30:00", "15:00:00", "10:00:00", "16:30:00", "06:30:00", "21:30:00", true, "12:00:00");
