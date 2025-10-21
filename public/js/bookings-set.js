const {
    weekDates,
    weekDatesForShow,
    todayIndex,
    weekDays,
    startTime,
    endTime,
    startTimeW1,
    endTimeW1,
    startTimeW2,
    endTimeW2,
    startTimeH,
    endTimeH,
    hasLaunchTime,
    launchTime,
    todayWord,
    w1Index,
    w2Index,
    hIndexes,
    serviceObj,
    prebookingList
} = window.scheduleConfig;
// console.log((serviceObj))
// console.log((serviceObj[0]))
// console.log("prebookingList : ",(prebookingList))
// for(idx=0;idx<prebookingList.length;idx++){
//     console.log(prebookingList[idx].id)
//     console.log(prebookingList[idx].user_id)
//     console.log("date:",prebookingList[idx].date)
//     console.log("time:",prebookingList[idx].time)
// }
const pageSize = 7;
const getWeekSlice = (weekDates, weekNumber) =>
    weekDates.slice((weekNumber - 1) * pageSize, weekNumber * pageSize);
let weekNumber = 1
let selectedWeekDates = getWeekSlice(weekDates, weekNumber)
let selectedWeekDatesForShow = getWeekSlice(weekDatesForShow, weekNumber)

function generateGrid(columns, startTime, endTime, w1Index, startTimeW1, endTimeW1, w2Index, startTimeW2, endTimeW2, hIndexes, startTimeH, endTimeH, hasLaunchTime, launchTIme, serviceObj) {
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
    let maxDiff = (maxEnd - minStart) / 2;
    maxDiff % 2 === 1 ? maxDiff + 1 : maxDiff


    rows = maxDiff + 1
    gridContainer.innerHTML = '';
    tmpstart = minStart;
    cellnumber = 1;

    // console.log("minStart->", minStart)
    // console.log("maxEnd->", maxEnd)
    // console.log("maxDiff->", maxDiff)
    // console.log("rows->", rows)

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
            text = `${weekDays[i - 1]} ${selectedWeekDatesForShow[i - 1]}`;
        } else if (i % columns === 0) {
            rstart = Math.floor(i / columns) + cc;
            rend = Math.floor(i / columns) + cc + 2;
            cc++;
            text = ` ${unitsToTime(tmpstart)} - ${unitsToTime(tmpstart + 2)}`;
            tmpstart += 2;
        } else {
            continue;
        }

        gridContainer.appendChild(createBox(rend, columnStart, rstart, text, 'box', 'checkAll-col-' + i, "", true, (i > 0 && (i - 1 === todayIndex)) ? todayWord : ''));
    }
    duration = serviceObj.estimated_duration * 30
    tmpstart = minStart;

    for (let i = 1; i < columns; i++) {
        let j = 1
        const endOfOffForEnd = maxEnd - minStart + 2;
        const gridColumn = i + 1;
        let workRange = 0
        let startOfOffForEnd = 0;
        let startOfLaunchTime = startLaunch - minStart + 2;
        if ((i - 1) === w2Index) {//jome
            if (startUnit6 > minStart) {
                const endOfOffForStart = startUnit6 - minStart + 2;
                gridContainer.appendChild(createBox(endOfOffForStart, gridColumn, 2, "", "disabledBox"));
                j = startUnit6 - minStart + 1
            }
            if (hasLaunchTime && startLaunch >= startUnit6 && startLaunch < endUnit6) {
                gridContainer.appendChild(createBox(startLaunch - minStart + 3, gridColumn, startOfLaunchTime, "", "launchBox"));
            }
            startOfOffForEnd = endUnit6 - minStart + 2;
            if ((endUnit6) < maxEnd) {
                gridContainer.appendChild(createBox(endOfOffForEnd, gridColumn, startOfOffForEnd, "", "disabledBox"));
            }
            workRange = endUnit6 - startUnit6
        } else if ((i - 1) === w1Index) {
            if (startUnit5 > minStart) {
                const endOfOffForStart = startUnit5 - minStart + 2;
                gridContainer.appendChild(createBox(endOfOffForStart, gridColumn, 2, "", "disabledBox"));
                j = startUnit5 - minStart + 1
            }
            if (hasLaunchTime && startLaunch >= startUnit5 && startLaunch < endUnit5) {
                gridContainer.appendChild(createBox(startLaunch - minStart + 3, gridColumn, startOfLaunchTime, "", "launchBox"));
            }
            startOfOffForEnd = endUnit5 - minStart + 2;
            if ((endUnit5) < maxEnd) {
                gridContainer.appendChild(createBox(endOfOffForEnd, gridColumn, startOfOffForEnd, "", "disabledBox"));
            }
            workRange = endUnit5 - startUnit5
        } else {
            if (hIndexes.includes(i - 1)) {
                if (startUnith > minStart) {
                    const endOfOffForStart = startUnith - minStart + 2;
                    gridContainer.appendChild(createBox(endOfOffForStart, gridColumn, 2, "", "disabledBox"));
                    j = startUnith - minStart + 1
                }
                if (hasLaunchTime && startLaunch >= startUnith && startLaunch < endUnith) {
                    gridContainer.appendChild(createBox(startLaunch - minStart + 3, gridColumn, startOfLaunchTime, "", "launchBox", null, "#f320000"));
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
                startOfOffForEnd = endUnit - minStart + 2;
                if ((endUnit) < maxEnd) {
                    gridContainer.appendChild(createBox(endOfOffForEnd, gridColumn, startOfOffForEnd, "", "disabledBox"));
                }
                workRange = endUnit - startUnit
            }
        }
        // console.log(weekDays[i - 1] + "->workRange:" + workRange + " start:" + (j) + " end:" + ((startOfOffForEnd)))
        for (; j < (startOfOffForEnd - (duration / 30));) {
            if (hasLaunchTime && j < startOfLaunchTime && (j + (duration / 30)) >= startOfLaunchTime) {
                j = startOfLaunchTime
            } else {
                // console.log(unitsToTime(j + minStart - 1))
                let find = prebookingList.find(item => item.date === selectedWeekDates[i] && item.time ===unitsToTime(j + minStart - 1));
                let bgColor =(find!==undefined)? "" :( i - 1 === w1Index ? "#f3550033" : i - 1 === w2Index ? "#f3005033" : i - 1 === w2Index ? "#f5000033" : "");
                let text1 = serviceObj.title   +((find!==undefined&&(find.status==2||find.status==1))?'<span class="badge bg-success">رزرو شده</span>':'')+ "<br>" + ` ${unitsToTime(j + minStart - 1)} - ${unitsToTime(j + minStart - 1 + (duration / 30))}`;
                gridContainer.appendChild(
                    createBox(j + 1 + (duration / 30),
                        gridColumn, j + 1,
                        text1 ,
                        ["box", "cell"],
                        'col-' + i + '_row-' + j,  bgColor,false,'',
                        (find!==undefined)&&find.service_id==serviceObj.service_id,selectedWeekDates[i],
                    unitsToTime(j + minStart - 1),serviceObj.service_id,serviceObj.id));
                if ((find!==undefined)){
                    // console.log("service_id:",find.service_id);
                    // console.log("serviceObj.id:",serviceObj.service_id);
                }
                // console.log("service_id:"+(find!==undefined)?find.service_id:'not found');
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

function createBox(end, gridColumn, gridRowStart, text, classnames = "box", checkboxId = null,
                   bgColor = '', header = false, todayWord = '',selected=false,
                   date=null,time=null,serviceId=null,employeeServiceId=null) {
    const box = document.createElement('div');
    if (Array.isArray(classnames)) {
        classnames.forEach(cls => box.classList.add(cls));
    } else {
        box.classList.add(classnames);
    }

    box.innerHTML = ((todayWord !== '') ? '<span class="badge bg-success mx-1">' + todayWord + '</span>' : '') + text;
    box.style.gridColumnStart = gridColumn;
    box.style.gridRowStart = gridRowStart;
    box.style.gridRowEnd = end;
    box.style.backgroundColor = bgColor;

    const checkbox = document.createElement('input');
    checkbox.type = 'checkbox';

    if (checkboxId && gridColumn !== 1) {
        checkbox.id = checkboxId;
        checkbox.checked=selected
        if (!header) {
            checkbox.name = 'selected[]';
            checkbox.value = JSON.stringify({
                date: date || '',
                time: time || '',
                service_id: serviceId || '',
                employeeServiceId: employeeServiceId || ''
            });
        }
        box.appendChild(checkbox);
        checkbox.addEventListener('change', function () {
            if (this.id.startsWith('checkAll-col-')) {
                const colNumber = this.id.split('-')[2];
                const checkboxes = document.querySelectorAll(`input[id^="col-${colNumber}_row-"]`);

                checkboxes.forEach(cb => {
                    cb.checked = this.checked;
                });
                let diffCHeckBox1 = diffCHeckBox();
                if (diffCHeckBox1 !== undefined && diffCHeckBox1.length > 0) {
                    startTask()
                } else {
                    finishTask();
                }
            }
        });

        box.addEventListener('click', function (event) {
            if (event.target.type === 'checkbox') {
                return;
            }

            checkbox.checked = !checkbox.checked;
            checkbox.dispatchEvent(new Event('change'));
            let diffCHeckBox1 = diffCHeckBox();
            if (diffCHeckBox1 !== undefined && diffCHeckBox1.length > 0) {
                startTask()
            } else {
                finishTask();
            }
        });

        box.getCheckbox = function () {
            return checkbox;
        };

        box.setChecked = function (checked) {
            checkbox.checked = checked;
            checkbox.dispatchEvent(new Event('change'));
        };

        box.isChecked = function () {
            return checkbox.checked;
        };
        addCheckBox(checkbox);
    }
    return box;
}

let checkBoxes = []
const addCheckBox = (el) => checkBoxes.push({el, last: !!el.checked});
const diffCHeckBox = () => checkBoxes.filter(x => !!x.el.checked !== x.last);

let taskInProgress = false;

function startTask() {
    console.log("startTask");
    $('#reserveBtn').prop('disabled', false);
    taskInProgress = true;
}

function finishTask() {
    console.log("finishTask");
    taskInProgress = false;
    $('#reserveBtn').prop('disabled', true);
}

// window.addEventListener('beforeunload', (event) => {
//     if (taskInProgress) {
//         event.preventDefault();
//         event.returnValue = '';
//     }
// });


$(document).ready(function () {
    $('#employeeService').on('change', function (e) {
        let id = $(this).val();
        let serviceObj1 = serviceObj.find(s => Number(s.id) === Number(id));
        console.log("selected->", serviceObj1.id)
        generateGrid(8, startTime, endTime, w1Index, startTimeW1, endTimeW1, w2Index, startTimeW2, endTimeW2, hIndexes, startTimeH, endTimeH, hasLaunchTime, launchTime, serviceObj1);
    })
    generateGrid(8, startTime, endTime, w1Index, startTimeW1, endTimeW1, w2Index, startTimeW2, endTimeW2, hIndexes, startTimeH, endTimeH, hasLaunchTime, launchTime, serviceObj[0]);
    $('#nextWeek').on('click', function (e) {
        if (taskInProgress) {
                showConfirm({
                    title: 'هشدار',
                    message: 'در انتخاب ساعت ها تغییر داشتید، برای رفتن به صفحه بعدی ابتدا تغییرات راذخیره کنید و یا به حالت اولیه بازگردانید',
                    okText: 'ذخیره تغییرات',
                    cancelText: 'ذخیره نمیکنم، میرم صفحه بعدی',
                    onOk: function() {

                    },
                    onCancel: function() {
                        finishTask()
                        weekNumber++
                        selectedWeekDates = getWeekSlice(weekDates, weekNumber)
                        selectedWeekDatesForShow = getWeekSlice(weekDatesForShow, weekNumber)
                        generateGrid(8, startTime, endTime, w1Index, startTimeW1, endTimeW1, w2Index, startTimeW2, endTimeW2, hIndexes, startTimeH, endTimeH, hasLaunchTime, launchTime, serviceObj[0]);
                        $('#prevWeek').prop('disabled', (weekNumber <= 1));
                        $('#nextWeek').prop('disabled', (weekNumber >= 4));
                        $('#startDate').val(selectedWeekDates[0]);
                        $('#endDate').val(selectedWeekDates[6]);
                    }
                });
        }else {
            finishTask()
            weekNumber++
            selectedWeekDates = getWeekSlice(weekDates, weekNumber)
            selectedWeekDatesForShow = getWeekSlice(weekDatesForShow, weekNumber)
            generateGrid(8, startTime, endTime, w1Index, startTimeW1, endTimeW1, w2Index, startTimeW2, endTimeW2, hIndexes, startTimeH, endTimeH, hasLaunchTime, launchTime, serviceObj[0]);
            $('#prevWeek').prop('disabled', (weekNumber <= 1));
            $('#nextWeek').prop('disabled', (weekNumber >= 4));
            $('#startDate').val(selectedWeekDates[0]);
            $('#endDate').val(selectedWeekDates[6]);
        }

    })
    $('#prevWeek').on('click', function (e) {
        if (taskInProgress) {
            showConfirm({
                title: 'هشدار',
                message: 'در انتخاب ساعت ها تغییر داشتید، برای رفتن به صفحه بعدی ابتدا تغییرات راذخیره کنید و یا به حالت اولیه بازگردانید',
                okText: 'ذخیره تغییرات',
                cancelText: 'ذخیره نمیکنم، میرم صفحه قبلی',
                onOk: function() {

                },
                onCancel: function() {
                    finishTask()
                    weekNumber--
                    selectedWeekDates = getWeekSlice(weekDates, weekNumber)
                    selectedWeekDatesForShow = getWeekSlice(weekDatesForShow, weekNumber)
                    generateGrid(8, startTime, endTime, w1Index, startTimeW1, endTimeW1, w2Index, startTimeW2, endTimeW2, hIndexes, startTimeH, endTimeH, hasLaunchTime, launchTime, serviceObj[0]);
                    $('#prevWeek').prop('disabled', (weekNumber <= 1));
                    $('#nextWeek').prop('disabled', (weekNumber >= 4));
                    $('#startDate').val(selectedWeekDates[0]);
                    $('#endDate').val(selectedWeekDates[6]);
                }
            });
        }else {
            finishTask()
            weekNumber--
            selectedWeekDates = getWeekSlice(weekDates, weekNumber)
            selectedWeekDatesForShow = getWeekSlice(weekDatesForShow, weekNumber)
            generateGrid(8, startTime, endTime, w1Index, startTimeW1, endTimeW1, w2Index, startTimeW2, endTimeW2, hIndexes, startTimeH, endTimeH, hasLaunchTime, launchTime, serviceObj[0]);
            $('#prevWeek').prop('disabled', (weekNumber <= 1));
            $('#nextWeek').prop('disabled', (weekNumber >= 4));
            $('#startDate').val(selectedWeekDates[0]);
            $('#endDate').val(selectedWeekDates[6]);
        }


    })

})


/**
 * showConfirm(options) -> Promise<boolean>
 * options = {
 *   title: '...',               // پیش‌فرض: 'تأیید'
 *   message: '...',             // پیش‌فرض: ''
 *   okText: '...',              // پیش‌فرض: 'تأیید'
 *   cancelText: '...',          // پیش‌فرض: 'انصراف'
 *   size: 'sm'|'lg'|'xl'|'',    // پیش‌فرض: '' (نرمال)
 *   backdrop: 'static'|true|false, // پیش‌فرض: true
 *   keyboard: true|false,       // پیش‌فرض: true
 *   centered: true|false,       // پیش‌فرض: true
 *   onOk: ()=>{},               // اختیاری
 *   onCancel: ()=>{}            // اختیاری
 * }
 */
function showConfirm(options = {}) {
    const {
        title = 'تأیید',
        message = '',
        okText = 'تأیید',
        cancelText = 'انصراف',
        size = '',
        backdrop = true,
        keyboard = true,
        centered = true,
        onOk,
        onCancel
    } = options;

    // شناسه یکتا برای جلوگیری از تداخل
    const uid = 'confirmModal_' + Date.now();
    const sizeClass = size ? `modal-${size}` : '';
    const centeredClass = centered ? 'modal-dialog-centered' : '';

    // اگه مودالی با همین آیدی از قبل بود پاکش کن
    $('#' + uid).remove();

    // تمپلیت مودال
    const $modal = $(`
      <div class="modal fade" id="${uid}" tabindex="-1" aria-hidden="true" dir="rtl">
        <div class="modal-dialog ${sizeClass} ${centeredClass}">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">${title}</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
            </div>
            <div class="modal-body">
              ${message}
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-role="cancel">${cancelText}</button>
              <button type="button" class="btn btn-primary" data-role="ok">${okText}</button>
            </div>
          </div>
        </div>
      </div>
    `);

    $('body').append($modal);

    const bsModal = new bootstrap.Modal($modal[0], { backdrop, keyboard });

    return new Promise((resolve) => {
        // کلیک روی OK
        $modal.find('[data-role="ok"]').on('click', () => {
            onOk && onOk();     // اگر callback داده شده
            resolve(true);      // Promise => true
            bsModal.hide();
        });

        // کلیک روی Cancel یا بستن با ×
        const cancelHandler = () => {
            onCancel && onCancel();
            resolve(false);     // Promise => false
        };
        $modal.find('[data-role="cancel"]').on('click', () => {
            cancelHandler();
            bsModal.hide();
        });
        $modal.on('hide.bs.modal', () => {
            // اگر با ESC یا کلیک بیرون بسته شد و هنوز resolve نشده
            // اینجا resolve انجام میشه فقط اگر قبلاً OK کلیک نشده باشه
        });
        $modal.on('hidden.bs.modal', () => {
            $modal.remove(); // پاکسازی از DOM
        });

        // نمایش مودال
        bsModal.show();
    });
}