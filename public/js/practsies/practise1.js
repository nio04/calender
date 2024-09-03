import { hideSpinner, render, showSpinner } from "../helper/helper.js";


export async function autoLoadEvents(route) {
  try {
    showSpinner()
    const res = await fetch(route);
    const data = await res.json();
    
    render("#result", data.map(d=>`<p class="border-2 border-blue-200 pl-2">${d.title}</p>`).join(""))
  } catch (error) {
  console.error(error);
  } finally {
    hideSpinner()
  }
  
}
