/**
 * SOFIA SOLUTIONS - Motor de Simulación Geoespacial
 * Genera telemetría de ciberseguridad para demostraciones académicas.
 */

export interface GeoAttackEvent {
  city: string;
  country: string;
  latitude: number;
  longitude: number;
  attack_type: 'BRUTE_FORCE' | 'SQLI' | 'XSS' | 'SESSION_HIJACKING' | 'DOS';
  attempts: number;
  blocked: boolean;
  severity: 'low' | 'medium' | 'high' | 'critical';
  timestamp: string;
}

const MOCKUP_LOCATIONS = [
  { city: 'Madrid', country: 'Spain', latitude: 40.4168, longitude: -3.7038 },
  { city: 'Barcelona', country: 'Spain', latitude: 41.3851, longitude: 2.1734 },
  { city: 'Nueva York', country: 'USA', latitude: 40.7128, longitude: -74.0060 },
  { city: 'Londres', country: 'UK', latitude: 51.5074, longitude: -0.1278 },
  { city: 'Moscú', country: 'Russia', latitude: 55.7558, longitude: 37.6173 },
  { city: 'Sídney', country: 'Australia', latitude: -33.8688, longitude: 151.2093 },
  { city: 'Singapur', country: 'Singapore', latitude: 1.3521, longitude: 103.8198 },
  { city: 'São Paulo', country: 'Brazil', latitude: -23.5505, longitude: -46.6333 }
];

const ATTACK_TYPES: GeoAttackEvent['attack_type'][] = [
  'BRUTE_FORCE', 'BRUTE_FORCE', 'BRUTE_FORCE', 'BRUTE_FORCE', 
  'SQLI', 'SQLI', 
  'XSS', 
  'SESSION_HIJACKING', 
  'DOS'
];

const getRandomAttackType = (): GeoAttackEvent['attack_type'] => 
  ATTACK_TYPES[Math.floor(Math.random() * ATTACK_TYPES.length)];

const getRandomLocation = () => 
  MOCKUP_LOCATIONS[Math.floor(Math.random() * MOCKUP_LOCATIONS.length)];

const getRandomAttempts = (): number => Math.floor(Math.random() * 15) + 1;

const isBlocked = (): boolean => Math.random() > 0.25; 

const calculateSeverity = (blocked: boolean, attempts: number, attackType: string): GeoAttackEvent['severity'] => {
  if (!blocked && attackType === 'SQLI') return 'critical';
  if (attempts >= 10) return 'high';
  if (blocked && attempts < 5) return 'low';
  return 'medium';
};

export function generateMockupAttacks(): GeoAttackEvent[] {
  const count = Math.floor(Math.random() * (12 - 8 + 1)) + 8;
  
  return Array.from({ length: count }).map(() => {
    const loc = getRandomLocation();
    const attempts = getRandomAttempts();
    const blocked = isBlocked();
    const type = getRandomAttackType();
    
    return {
      ...loc,
      attack_type: type,
      attempts,
      blocked,
      severity: calculateSeverity(blocked, attempts, type),
      timestamp: new Date().toISOString()
    };
  });
}
