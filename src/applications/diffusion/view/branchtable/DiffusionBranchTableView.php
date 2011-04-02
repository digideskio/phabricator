<?php

/*
 * Copyright 2011 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

final class DiffusionBranchTableView extends DiffusionView {

  private $branches;

  public function setBranches(array $branches) {
    $this->branches = $branches;
    return $this;
  }

  public function render() {
    $drequest = $this->getDiffusionRequest();
    $current_branch = $drequest->getBranch();

    $callsign = $drequest->getRepository()->getCallsign();

    $rows = array();
    $rowc = array();
    foreach ($this->branches as $branch) {
      $branch_uri = $drequest->getBranchURIComponent($branch->getName());

      $rows[] = array(
        phutil_render_tag(
          'a',
          array(
            'href' => "/diffusion/{$callsign}/repository/{$branch_uri}",
          ),
          phutil_escape_html($branch->getName())),
        self::linkCommit(
          $drequest->getRepository(),
          $branch->getHeadCommitIdentifier()),
        // TODO: etc etc
      );
      if ($branch->getName() == $current_branch) {
        $rowc[] = 'highlighted';
      } else {
        $rowc[] = null;
      }
    }

    $view = new AphrontTableView($rows);
    $view->setHeaders(
      array(
        'Branch',
        'Head',
      ));
    $view->setColumnClasses(
      array(
        'wide',
      ));
    $view->setRowClasses($rowc);
    return $view->render();
  }

}
