const Calendar={days:{Mon:"Lundi",Tue:"Mardi",Wed:"Mercredi",Thu:"Jeudi",Fri:"Vendredi",Sat:"Samedi",Sun:"Dimanche"},months:{Jan:"Janvier",Feb:"Février",Mar:"Mars",Apr:"Avril",May:"Mai",Jun:"Juin",Jul:"Juillet",Aug:"Âout",Sep:"Septembre",Oct:"Octobre",Nov:"Novembre",Dec:"Décembre"},periodes:null,inputs:{start:$("#dateStart"),end:$("#dateEnd")},offset:0,events:!1,start:null,end:null,today:null,init:async()=>{Calendar.container=$(".js-calendar"),Calendar.head_date=$(".calendar__head--date"),Calendar.head_container=$(".calendar__head--container"),Calendar.body_container=$(".calendar__body"),Calendar.today=new Date,Calendar.offsetdate=new Date,Calendar.offsetdate.setMonth(Calendar.today.getMonth()-Calendar.offset),Calendar.events||(Calendar.inputs.start.min=Calendar.today.toISOString().split("T")[0],Calendar.inputs.start.value="",Calendar.inputs.end.value="",Calendar.head_container.addEventListener("click",(e=>{let a=e.target.closest(".calendar__head--switch");a&&a.dataset.switch&&(Calendar.offset+="1"===a.dataset.switch?1:-1,Calendar.init())})),Calendar.inputs.start.addEventListener("change",(e=>{Calendar.inputs.end.min=Calendar.inputs.start.value})),Calendar.body_container.addEventListener("click",Calendar.selectPeriode),Calendar.body_container.addEventListener("dblclick",(()=>{Calendar.inputs.start.value="",Calendar.inputs.end.value="",Calendar.offset=0,Calendar.init()})),Calendar.container.addEventListener("keypress",(e=>{"Enter"===e.key&&e.target.click()})),Calendar.events=!0),Calendar.head(),Calendar.body()},head:()=>{Calendar.head_date.innerText=`${Object.values(Calendar.months)[Calendar.offsetdate.getMonth()]} ${Calendar.offsetdate.getFullYear()}`},body:()=>{Calendar.body_container.innerHTML=null;let e=Calendar.offsetdate;e.setDate(1),e.setMonth(e.getMonth()+1),e.setDate(e.getDate()-1);let a=e.getDate(),t=Object.keys(Calendar.days).length-Object.keys(Calendar.days).indexOf(e.toString().substring(0,3))-1;e.setDate(1);let n=Object.keys(Calendar.days).indexOf(e.toString().substring(0,3));function d(e,a){return`<i class="block__day ${a}" tabindex="0" data-date="${e.toISOString().split("T")[0]}">${e.getDate()}</i>`}e.setDate(e.getDate()-n);let r=a+n+t,s=!0;for(let t=0;t<r;t++){if(e.toDateString()===Calendar.today.toDateString())Calendar.body_container.innerHTML+=d(e,"current"),s=!1;else{let r="futur";s&&(r="past"),Calendar.offset<0&&(r="futur"),t-n+1>a&&(r="next"),t<n&&(r="before"),Calendar.body_container.innerHTML+=d(e,r)}e.setDate(e.getDate()+1)}if(Calendar.periodes){let e=[...Calendar.periodes],a=!1;""!==Calendar.inputs.start.value&&(a=!0,e.push({start:Calendar.inputs.start.value,end:Calendar.inputs.end.value})),e.forEach(((t,n)=>{let d=$(`.block__day[data-date="${t.start}"]`),r=$(`.block__day[data-date="${t.end}"]`);if(d?.classList.add(n===e.length-1&&a?"cursorpinstart":"pinstart"),""===t.end)return;if(null===r&&t.end>$(".block__day:last-child").dataset.date&&t.start<$(".block__day:last-child").dataset.date&&(r=$(".block__day:last-child"),r.classList.add(n===e.length-1&&a?"cursorperiode":"periode")),null===d&&t.end>$(".block__day:first-child").dataset.date&&t.start<$(".block__day:first-child").dataset.date&&(d=$(".block__day:first-child"),d.classList.add(n===e.length-1&&a?"cursorperiode":"periode")),null===r||null===d)return;d?.classList.add(n===e.length-1&&a?"cursorpinstart":"pinstart"),r?.classList.add(n===e.length-1&&a?"cursorpinend":"pinend");let s=0,l=r.previousElementSibling;for(;s<40&&null!=l&&!l.classList.contains(n===e.length-1&&a?"cursorpinstart":"pinstart");)l.classList.add(n===e.length-1&&a?"cursorperiode":"periode"),l=l.previousElementSibling,s++}))}},selectPeriode:e=>{let a=e.target.closest(".block__day");if(a&&a.dataset.date){if(a.classList.contains("past")||a.classList.contains("before"))return;if(a.classList.contains("pinstart")||a.classList.contains("pinend")||a.classList.contains("periode"))return;if(""===Calendar.inputs.start.value)Calendar.inputs.start.value=a.dataset.date;else{if(a.dataset.date<Calendar.inputs.start.value)return;if(Calendar.inputs.start.value===a.dataset.date)Calendar.inputs.start.value="",Calendar.inputs.end.value="";else{if(Calendar.periodes.filter((e=>Calendar.inputs.start.value<=e.end&&a.dataset.date>=e.start)).length>0)return;Calendar.inputs.end.value=a.dataset.date}}Calendar.init()}},importPeriodes:e=>{Calendar.periodes=e,Calendar.init()}};document.addEventListener("DOMContentLoaded",Calendar.init);