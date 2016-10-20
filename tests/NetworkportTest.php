<?php
/*
-------------------------------------------------------------------------
GLPI - Gestionnaire Libre de Parc Informatique
Copyright (C) 2015-2016 Teclib'.

http://glpi-project.org

based on GLPI - Gestionnaire Libre de Parc Informatique
Copyright (C) 2003-2014 by the INDEPNET Development Team.

-------------------------------------------------------------------------

LICENSE

This file is part of GLPI.

GLPI is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

GLPI is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with GLPI. If not, see <http://www.gnu.org/licenses/>.
--------------------------------------------------------------------------
*/

/* Test for inc/networkport.class.php */

class NetworkportTest extends DbTestCase {

   /**
    * @covers NetworkPort::prepareInputForAdd
    * @covers NetworkPort::post_addItem
    */
   public function testAddSimpleNetworkPort() {
      $this->Login();

      $computer1 = getItemByTypeName('Computer', '_test_pc01');

      $ins = new NetworkPort();
      // Be sure added
      $nb_log = countElementsInTable('glpi_logs');
      $this->assertGreaterThan(0, $ins->add([
         'items_id'           => $computer1->getID(),
         'itemtype'           => 'Computer',
         'entities_id'        => $computer1->fields['entities_id'],
         'is_recursive'       => 0,
         'logical_number'     => 1,
         'mac'                => '00:24:81:eb:c6:d0',
         'instantiation_type' => 'NetworkPortEthernet',
         'name'               => 'eth1',
      ]));
      $this->assertGreaterThan($nb_log, countElementsInTable('glpi_logs'));

      // check data in db
      $networkports = end(getAllDatasFromTable('glpi_networkports', '', false, 'id'));
      unset($networkports['id']);
      unset($networkports['date_mod']);
      unset($networkports['date_creation']);
      $expected = [
          'items_id'           => $computer1->getID(),
          'itemtype'           => 'Computer',
          'entities_id'        => $computer1->fields['entities_id'],
          'is_recursive'       => '0',
          'logical_number'     => '1',
          'name'               => 'eth1',
          'instantiation_type' => 'NetworkPortEthernet',
          'mac'                => '00:24:81:eb:c6:d0',
          'comment'            => null,
          'is_deleted'         => '0',
          'is_dynamic'         => '0',
      ];
      $this->assertEquals($expected, $networkports);

      // be sure added and have no logs
      $nb_log = countElementsInTable('glpi_logs');
      $this->assertGreaterThan(0, $ins->add([
         'items_id'           => $computer1->getID(),
         'itemtype'           => 'Computer',
         'entities_id'        => $computer1->fields['entities_id'],
         'logical_number'     => 2,
         'mac'                => '00:24:81:eb:c6:d1',
         'instantiation_type' => 'NetworkPortEthernet',
      ], [], false));
      $this->assertEquals($nb_log, countElementsInTable('glpi_logs'));
   }

   /**
    * @covers NetworkPort::prepareInputForAdd
    * @covers NetworkPort::post_addItem
    */
   public function testAddCompleteNetworkPort() {
      $this->Login();

      $computer1 = getItemByTypeName('Computer', '_test_pc01');

      // Do some installations
      $ins = new NetworkPort();
      // Be sure added
      $nb_log = countElementsInTable('glpi_logs');
      $this->assertGreaterThan(0, $ins->add([
         'items_id'           => $computer1->getID(),
         'itemtype'           => 'Computer',
         'entities_id'        => $computer1->fields['entities_id'],
         'is_recursive'       => 0,
         'logical_number'     => 3,
         'mac'                => '00:24:81:eb:c6:d2',
         'instantiation_type' => 'NetworkPortEthernet',
         'name'               => 'em3',
         'comment'            => 'Comment me!',
         'netpoints_id'       => 0,
         'items_devicenetworkcards_id' => 0,
         'type'               => 'T',
         'speed'              => 1000,
         'speed_other_value'  => '',
         'NetworkName_name'   => 'test1.me',
         'NetworkName_fqdns_id' => 0,
         'NetworkName__ipaddresses' => ['-1' => '192.168.20.1']
      ]));
      $this->assertGreaterThan($nb_log, countElementsInTable('glpi_logs'));

      // be sure added and have no logs
      $nb_log = countElementsInTable('glpi_logs');
      $this->assertGreaterThan(0, $ins->add([
         'items_id'           => $computer1->getID(),
         'itemtype'           => 'Computer',
         'entities_id'        => $computer1->fields['entities_id'],
         'is_recursive'       => 0,
         'logical_number'     => 4,
         'mac'                => '00:24:81:eb:c6:d4',
         'instantiation_type' => 'NetworkPortEthernet',
         'name'               => 'em4',
         'comment'            => 'Comment me!',
         'netpoints_id'       => 0,
         'items_devicenetworkcards_id' => 0,
         'type'               => 'T',
         'speed'              => 1000,
         'speed_other_value'  => '',
         'NetworkName_name'   => 'test2.me',
         'NetworkName_fqdns_id' => 0,
         'NetworkName__ipaddresses' => ['-1' => '192.168.20.2']
      ], [], false));
      $this->assertEquals($nb_log, countElementsInTable('glpi_logs'));
   }
}