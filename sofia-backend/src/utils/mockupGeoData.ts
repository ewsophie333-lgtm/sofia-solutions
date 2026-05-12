/**
 * SOFIA SOLUTIONS - Mockup GeoData Engine
 * Utility to generate synthetic security telemetry for TFG demonstration.
 */

export type AttackType = 'BRUTE_FORCE' | 'SQLI' | 'XSS' | 'SESSION_HIJACKING' | 'DOS';
export type SeverityLevel = 'low' | 'medium' | 'high' | 'critical';

export interface GeoAttackEvent {
    city: string;
    country: string;
    latitude: number;
    longitude: number;
    attack_type: AttackType;
    attempts: number;
    blocked: boolean;
    severity: SeverityLevel;
    timestamp: string;
}

const LOCATIONS = [
    { city: 'Madrid', country: 'Spain', lat: 40.4168, lon: -3.7038 },
    { city: 'Barcelona', country: 'Spain', lat: 41.3851, lon: 2.1734 },
    { city: 'Nueva York', country: 'USA', lat: 40.7128, lon: -74.0060 },
    { city: 'Londres', country: 'UK', lat: 51.5074, lon: -0.1278 },
    { city: 'Moscú', country: 'Russia', lat: 55.7558, lon: 37.6173 },
    { city: 'Sídney', country: 'Australia', lat: -33.8688, lon: 151.2093 },
    { city: 'Singapur', country: 'Singapore', lat: 1.3521, lon: 103.8198 },
    { city: 'São Paulo', country: 'Brazil', lat: -23.5505, lon: -46.6333 }
];

const ATTACK_TYPES: AttackType[] = ['BRUTE_FORCE', 'SQLI', 'XSS', 'SESSION_HIJACKING', 'DOS'];
const SEVERITIES: SeverityLevel[] = ['low', 'medium', 'high', 'critical'];

export function generateMockupAttacks(): GeoAttackEvent[] {
    const eventCount = Math.floor(Math.random() * (12 - 8 + 1)) + 8;
    const events: GeoAttackEvent[] = [];

    for (let i = 0; i < eventCount; i++) {
        const location = LOCATIONS[Math.floor(Math.random() * LOCATIONS.length)];
        const attackType = ATTACK_TYPES[Math.floor(Math.random() * ATTACK_TYPES.length)];
        
        let severity: SeverityLevel = SEVERITIES[Math.floor(Math.random() * SEVERITIES.length)];
        if (attackType === 'DOS' || attackType === 'SQLI') severity = 'critical';

        events.push({
            city: location.city,
            country: location.country,
            latitude: location.lat,
            longitude: location.lon,
            attack_type: attackType,
            attempts: Math.floor(Math.random() * 15) + 1,
            blocked: Math.random() < 0.75,
            severity: severity,
            timestamp: new Date().toISOString()
        });
    }

    return events;
}
