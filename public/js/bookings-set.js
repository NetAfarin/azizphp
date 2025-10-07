

function generateGrid(columns, startTime, endTime, startTimeW1, endTimeW1, startTimeW2, endTimeW2, startTimeH, endTimeH, hasLaunchTime, launchTIme) {
    const gridContainer = document.getElementById('grid-container');
    let startUnit = timeToUnits(startTime);
    let endUnit = timeToUnits(endTime);
    let startUnit5 = timeToUnits(startTimeW1);
    let endUnit5 = timeToUnits(endTimeW1);
    let startUnit6 = timeToUnits(startTimeW2);
    let endUnit6 = timeToUnits(endTimeW2);
    let startUnith = timeToUnits(startTimeH);
    let endUnith = timeToUnits(endTimeH);
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

        gridContainer.appendChild(createBox(rend, columnStart, rstart, text,'box','checkAll-col-'+i));
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
                gridContainer.appendChild(createBox(j + 1 + (duration / 30), gridColumn, j + 1, "کاشت ناخن" + "<br>" + ` ${unitsToTime(j + minStart - 1)} - ${unitsToTime(j + minStart - 1 + (duration / 30))}`+ "<br>", ["box", "cell"],'col-'+i+'_row-'+j));
                j += (duration / 30)
            }
        }

    }
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

function createBox(end, gridColumn, gridRowStart, text, classnames = "box", checkboxId = null) {
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

    const checkbox = document.createElement('input');
    checkbox.type = 'checkbox';

    if (checkboxId && gridColumn!==1) {
        checkbox.id = checkboxId;
        box.appendChild(checkbox);
        checkbox.addEventListener('change', function() {
            if (this.id.startsWith('checkAll-col-')) {
                const colNumber = this.id.split('-')[2];
                const checkboxes = document.querySelectorAll(`input[id^="col-${colNumber}_row-"]`);

                checkboxes.forEach(cb => {
                    cb.checked = this.checked;
                });
            }
        });

        box.addEventListener('click', function(event) {
            if (event.target.type === 'checkbox') {
                return;
            }

            checkbox.checked = !checkbox.checked;
            checkbox.dispatchEvent(new Event('change'));
        });

        box.getCheckbox = function() {
            return checkbox;
        };

        box.setChecked = function(checked) {
            checkbox.checked = checked;
            checkbox.dispatchEvent(new Event('change'));
        };

        box.isChecked = function() {
            return checkbox.checked;
        };

    }
    return box;
}
const {
    startTime,
    endTime,
    startTimeW1,
    endTimeW1,
    startTimeW2,
    endTimeW2,
    startTimeH,
    endTimeH,
    hasLaunchTime,
    launchTime
} = window.scheduleConfig;

// let startTime = "09:00:00";
// let endTime = "19:30:00";
// let startTimeW1 = "08:30:00";
// let endTimeW1 = "15:00:00";
// let startTimeW2 = "10:00:00";
// let endTimeW2 = "16:30:00";
// let startTimeH = "06:30:00";
// let endTimeH = "21:30:00";
// let hasLaunchTime = true;
// let launchTIme = "12:00:00";
generateGrid(8, startTime, endTime, startTimeW1, endTimeW1, startTimeW2, endTimeW2, startTimeH, endTimeH, hasLaunchTime, launchTime);
