import { Component, OnInit } from '@angular/core';

import { DirtyDrainsPipe } from './../drains.pipe';
import { DrainsService } from './../../../core/drains.service';
import { Drain } from './../drain';

@Component({
  selector: 'dirty-drain',
  templateUrl: './dirty-drain.component.html',
  styleUrls: ['./dirty-drain.component.css'],
  providers: [],
})
export class DirtyDrainComponent implements OnInit {
  title = 'Dirty Drains';
  drains: Drain[];
  cleared = true;

  constructor(private drainService: DrainsService) { }
  getDrains(): void {
    this.drainService
        .getDirtyDrains()
        .subscribe(drains => this.drains = drains);
  }

  ngOnInit(): void {
    this.getDrains();
  }
}
