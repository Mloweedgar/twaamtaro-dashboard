import { Injectable, EventEmitter } from '@angular/core';
import {DrainsService} from "./drains.service";

@Injectable()
export class DataService {

  public onSearchResultsReady: EventEmitter<any[]> = new EventEmitter<any[]>();

  constructor(private needHelpService: DrainsService) { }

  storeNeedHelpRequests(data: any){
    localStorage.setItem('allNeedHelpRequests', JSON.stringify(data));
  }

  getNeedHelpRequests(){
    return JSON.parse(localStorage.getItem('allNeedHelpRequests'));
  }

  // searches need helps based on supplied
  // parameter
  searchNeedHelpRequests(searchParam){
    this.needHelpService.searchNeedHelps({column: 'street_name', key: searchParam})
      .subscribe( res => {
        console.log('emiting the response');
        this.onSearchResultsReady.emit(res);
      });

  }



}
