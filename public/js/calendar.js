const Calendar = {
    // FR Translation
    days: { "Mon": "Lundi", "Tue": "Mardi", "Wed": "Mercredi", "Thu": "Jeudi", "Fri": "Vendredi", "Sat": "Samedi", "Sun": "Dimanche" },
    months: {
        "Jan": "Janvier", "Feb": "Février", "Mar": "Mars", "Apr": "Avril", "May": "Mai", "Jun": "Juin", "Jul": "Juillet",
        "Aug": "Âout", "Sep": "Septembre", "Oct": "Octobre", "Nov": "Novembre", "Dec": "Décembre"
    },
    periodes: null,
    inputs: {
        start: $('#dateStart'),
        end: $('#dateEnd'),
    },
    offset: 0,
    events: false,
    start: null,
    end: null,
    today: null,
    init: async () => {
        Calendar.container = $('.js-calendar');
        Calendar.head_date = $('.calendar__head--date');
        Calendar.head_container = $('.calendar__head--container');
        Calendar.body_container = $('.calendar__body');
        Calendar.today = new Date();

        Calendar.offsetdate = new Date();
        Calendar.offsetdate.setMonth(Calendar.today.getMonth() - Calendar.offset);

        if(!Calendar.events) {
            Calendar.inputs.start.min = Calendar.today.toISOString().split("T")[0];
            Calendar.inputs.start.value = '';
            Calendar.inputs.end.value = '';

            Calendar.head_container.addEventListener('click', (evt) => {
                let target = evt.target.closest(".calendar__head--switch");
                if(!target || !target.dataset.switch) return;
                Calendar.offset += target.dataset.switch === "1" ? 1 : -1;
                Calendar.init();
            });
            Calendar.inputs.start.addEventListener('change', (evt) => {
                Calendar.inputs.end.min = Calendar.inputs.start.value;
            });
            Calendar.body_container.addEventListener('click', Calendar.selectPeriode);

            Calendar.body_container.addEventListener('dblclick', () => {
                Calendar.inputs.start.value = '';
                Calendar.inputs.end.value = '';
                Calendar.offset = 0;
                Calendar.init();
            });

            Calendar.container.addEventListener('keypress', (evt) => {
                if(evt.key === "Enter") {
                    evt.target.click();
                }
            });
            Calendar.events = true;
        }

        Calendar.head();
        Calendar.body();
    },
    head: () => {
        Calendar.head_date.innerText = `${Object.values(Calendar.months)[Calendar.offsetdate.getMonth()]} ${Calendar.offsetdate.getFullYear()}`
    },
    body: () => {
        Calendar.body_container.innerHTML = null;

        let walkdate = Calendar.offsetdate;

        walkdate.setDate(1);
        walkdate.setMonth(walkdate.getMonth() + 1);
        walkdate.setDate(walkdate.getDate() - 1);

        let maxdays = walkdate.getDate();
        let roffset = Object.keys(Calendar.days).length - Object.keys(Calendar.days).indexOf(walkdate.toString().substring(0, 3)) - 1;

        walkdate.setDate(1);

        let loffset = Object.keys(Calendar.days).indexOf(walkdate.toString().substring(0, 3));
        
        function draw (date, identifier) {
            let dao = (date.toISOString().split("T"))[0];
            let tabindex = identifier == "next" || identifier == "before" || identifier == "futur" ? 0 : -1;
            let ariadate = `${Calendar.days[date.toString().substring(0, 3)]} ${date.getDate()} ${Calendar.months[date.toString().substring(4, 7)]} ${date.getFullYear()}`;
            return `<i class="block__day ${identifier}" aria-label='${ariadate}' tabindex="${tabindex}" data-date="${dao}">${date.getDate()}</i>`;
        }

        walkdate.setDate(walkdate.getDate() - loffset);

        let max = maxdays + loffset + roffset;
        let past = true;
        
        for (let i = 0; i < max; i++) {
            if(walkdate.toDateString() === Calendar.today.toDateString()) {
                Calendar.body_container.innerHTML += draw(walkdate, "current");
                past = false;
            } else {
                let identifier = "futur";
                if(Calendar.offset < 0) identifier = "futur";
                if(i - loffset + 1 > maxdays) identifier = "next";
                if(i < loffset) identifier = "before";
                if(past) identifier = "past";
                Calendar.body_container.innerHTML += draw(walkdate, identifier);
            }
            walkdate.setDate(walkdate.getDate() + 1);
        }

        if(Calendar.periodes) {
            let periodes = [...Calendar.periodes];
            let cursor = false;

            if(Calendar.inputs.start.value !== '') {
                cursor = true;
                periodes.push({
                    start: Calendar.inputs.start.value,
                    end: Calendar.inputs.end.value
                });
            }
            
            periodes.forEach((periode, k) => {
                console.log(periode.start, periode.end);
                let pinstart = $(`.block__day[data-date="${periode.start}"]`);
                let pinend = $(`.block__day[data-date="${periode.end}"]`);

                pinstart?.classList.add(k === periodes.length - 1 && cursor ? 'cursorpinstart' : 'pinstart');
                if(periode.end === '') return;

                console.log(pinend);

                // if(!pinstart && periode.start > $(`.block__day:last-child`).dataset.date) return; 

                if(pinend === null
                    && periode.end > $(`.block__day:last-child`).dataset.date 
                    && periode.start < $(`.block__day:last-child`).dataset.date) {
                        pinend = $(`.block__day:last-child`);
                        pinend.classList.add(k === periodes.length - 1 && cursor ? 'cursorperiode' : 'periode')
                    }

                if(pinstart === null
                    && periode.end > $(`.block__day:first-child`).dataset.date 
                    && periode.start < $(`.block__day:first-child`).dataset.date) {
                        pinstart = $(`.block__day:first-child`);
                        pinstart.classList.add(k === periodes.length - 1 && cursor ? 'cursorperiode' : 'periode');
                    }

                if(pinend === null || pinstart === null) return;

                console.log(pinend);

                pinstart?.classList.add(k === periodes.length - 1 && cursor ? 'cursorpinstart' : 'pinstart');
                pinend?.classList.add(k === periodes.length - 1 && cursor ? 'cursorpinend' : 'pinend'); 

                pinstart?.setAttribute("tabindex", "0");
                pinend?.setAttribute("tabindex", "-1");
                
                let i = 0;
                let sibling = pinend.previousElementSibling;
                while(i < 40) {
                    if(sibling == null) break;
                    if(sibling.classList.contains(k === periodes.length - 1 && cursor ? 'cursorpinstart' : 'pinstart')) break;
                    sibling.classList.add(k === periodes.length - 1 && cursor ? 'cursorperiode' : 'periode');
                    sibling.setAttribute("tabindex", "-1");
                    sibling = sibling.previousElementSibling;
                    i++;
                };
            });
        }
    },
    selectPeriode: (evt) => {
        let target = evt.target.closest(".block__day");
        if(target && target.dataset.date) {
            if(target.classList.contains("past")) return;
            if(target.classList.contains("pinstart") || target.classList.contains("pinend")
                || target.classList.contains("periode")) return;

            if(Calendar.inputs.start.value === '') {
                Calendar.inputs.start.value = target.dataset.date;
            } else {
                if(target.dataset.date < Calendar.inputs.start.value) return;
                if(Calendar.inputs.start.value === target.dataset.date) {
                    Calendar.inputs.start.value = '';
                    Calendar.inputs.end.value = '';
                } else {
                    let overlap = Calendar.periodes.filter((item) => {
                        return Calendar.inputs.start.value <= item.end && target.dataset.date >= item.start;
                    });

                    if(overlap.length > 0) return;

                    Calendar.inputs.end.value = target.dataset.date
                }
            }
            Calendar.init();
        }
    },
    importPeriodes: (periodes) => {
        Calendar.periodes = periodes;
        Calendar.init();
        console.log(periodes);
    }
}

document.addEventListener('DOMContentLoaded', Calendar.init)